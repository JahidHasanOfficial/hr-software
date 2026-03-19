<?php

namespace App\Services;

use App\Models\Company;
use App\Helpers\ImageHelper;

class CompanyService
{
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
