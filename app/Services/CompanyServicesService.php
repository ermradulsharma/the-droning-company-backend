<?php
namespace App\Services;

use App\Models\CompanyService;
use Illuminate\Support\Str;

class CompanyServicesService
{
    public function company(int $company_id)
    {
        if (!$company_id) {
            return '';
        }
        $company_services = CompanyService::query()
            ->select('company_services.service_id as id', 'services.title')
            ->join('services', 'company_services.service_id', '=', 'services.id')
            ->where('company_services.company_id', $company_id)
            ->where('company_services.status', '1')
            ->get();

        return   Collect($company_services)->implode('title', ',');
    }

    public function companyAsArray(int $company_id)
    {
        if (!$company_id) {
            return '';
        }
        return $company_services = CompanyService::query()
            ->select('company_services.service_id as id', 'services.title')
            ->join('services', 'company_services.service_id', '=', 'services.id')
            ->where('company_services.company_id', $company_id)
            ->where('company_services.status', '1')
            ->get();
    }
	public function companyServicesArray(int $company_id)
    {
        if (!$company_id) {
            return '';
        }
        return $company_services = CompanyService::query()
            ->select('company_services.service_id as id', 'services.title')
            ->join('services', 'company_services.service_id', '=', 'services.id')
            ->where('company_services.company_id', $company_id)
            ->where('company_services.status', '1')
            ->get();
    }
}
