<?php

namespace App\Services;

use App\Models\Company;
use App\Helpers\ImageHelper;

class CompanyService
{
    /**
     * Get Paginated Companies
     */
    public function getAllCompanies($perPage = 10, $search = null)
    {
        $query = Company::query();
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
        }
        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function createCompany(array $data)
    {
        if (isset($data['logo'])) {
            $data['logo'] = ImageHelper::upload($data['logo'], 'companies');
        }
        return Company::create($data);
    }

    public function updateCompany(Company $company, array $data)
    {
        if (isset($data['logo'])) {
            $data['logo'] = ImageHelper::update($data['logo'], 'companies', $company->logo);
        }
        $company->update($data);
        return $company;
    }

    public function deleteCompany(Company $company)
    {
        if ($company->logo) {
            ImageHelper::delete($company->logo);
        }
        return $company->delete();
    }
}
