<?php

namespace App\View\Components;

use Illuminate\View\Component;

use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\Facades\Module;
use Modules\FormBuilder\Repositories\FormBuilderRepositories;
use Modules\FrontendCMS\Services\ContactContentService;
use Modules\FrontendCMS\Services\QueryService;

class ContactComponent extends Component
{
    protected $contactContentService;
    protected $queryService;
    public $headers;

    public function __construct($headers, ContactContentService $contactContentService, QueryService $queryService)
    {
        $this->headers = $headers;
        $this->contactContentService = $contactContentService;
        $this->queryService = $queryService;
    }

    public function render()
    {
        $contactContent = $this->contactContentService->getAll();
        $QueryList = $this->queryService->getAllActive();

        $row = '';
        $form_data = '';

        if (Module::has('FormBuilder') && Schema::hasTable('custom_forms')) {
            $formBuilderRepo = new FormBuilderRepositories();
            $row = $formBuilderRepo->find(4);

            if ($row && $row->form_data) {
                $form_data = json_decode($row->form_data);
            }
        }

        return view(theme('components.contact-component'), compact(
            'contactContent',
            'QueryList',
            'row',
            'form_data'
        ));
    }
}
