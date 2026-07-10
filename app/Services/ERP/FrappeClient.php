<?php

namespace App\Services\ERP;

use App\Models\ErpSetting;
use Illuminate\Support\Facades\Http;
use Exception;

class FrappeClient
{
    protected ?ErpSetting $setting;

    public function __construct()
    {
        // For simplicity, we assume there is only one setting record
        $this->setting = ErpSetting::first();
    }

    public function isConfigured(): bool
    {
        return $this->setting && $this->setting->erp_site_url && $this->setting->api_key && $this->setting->api_secret;
    }

    public function getSetting(): ?ErpSetting
    {
        return $this->setting;
    }

    protected function buildUrl(string $endpoint): string
    {
        $baseUrl = rtrim($this->setting->erp_site_url, '/');
        $endpoint = ltrim($endpoint, '/');
        return "{$baseUrl}/api/resource/{$endpoint}";
    }
    
    public function getMethodUrl(string $method): string
    {
        $baseUrl = rtrim($this->setting->erp_site_url, '/');
        $method = ltrim($method, '/');
        return "{$baseUrl}/api/method/{$method}";
    }

    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'token ' . $this->setting->api_key . ':' . $this->setting->api_secret,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    public function get(string $endpoint, array $query = [])
    {
        if (!$this->isConfigured()) {
            throw new Exception("ERP settings are not fully configured.");
        }

        $response = Http::withHeaders($this->getHeaders())
            ->timeout(30)
            ->retry(2, 500, throw: false)
            ->get($this->buildUrl($endpoint), $query);

        return $this->handleResponse($response);
    }

    public function getMethod(string $method, array $query = [])
    {
        if (!$this->isConfigured()) {
            throw new Exception("ERP settings are not fully configured.");
        }

        $response = Http::withHeaders($this->getHeaders())
            ->timeout(30)
            ->retry(2, 500, throw: false)
            ->get($this->getMethodUrl($method), $query);

        return $this->handleResponse($response);
    }

    public function testConnection(): string
    {
        $user = $this->getMethod('frappe.auth.get_logged_user');

        if (!is_string($user) || $user === '') {
            throw new Exception('ERP connection succeeded but returned an invalid user.');
        }

        return $user;
    }

    public function post(string $endpoint, array $data = [])
    {
        if (!$this->isConfigured()) {
            throw new Exception("ERP settings are not fully configured.");
        }

        $response = Http::withHeaders($this->getHeaders())
            ->timeout(30)
            ->retry(2, 500, throw: false)
            ->post($this->buildUrl($endpoint), $data);

        return $this->handleResponse($response);
    }

    public function put(string $endpoint, array $data = [])
    {
        if (!$this->isConfigured()) {
            throw new Exception("ERP settings are not fully configured.");
        }

        $response = Http::withHeaders($this->getHeaders())
            ->timeout(30)
            ->retry(2, 500, throw: false)
            ->put($this->buildUrl($endpoint), $data);

        return $this->handleResponse($response);
    }

    protected function handleResponse($response)
    {
        if ($response->successful()) {
            return $response->json('data') ?? $response->json('message');
        }

        $errorMsg = $response->json('exception')
            ?? $response->json('message')
            ?? $response->json('exc')
            ?? $response->body();
        throw new Exception("Frappe API Error ({$response->status()}): " . $errorMsg);
    }
}
