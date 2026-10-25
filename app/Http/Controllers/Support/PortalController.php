<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Models\HelpTopic;
use Illuminate\View\View;

/**
 * The support portal's start page: how it works, the help topics and the knowledge base.
 */
class PortalController extends Controller
{
    public function __invoke(): View
    {
        return view('support.home', [
            'topics' => HelpTopic::query()->availableToClients()->get(),
            'faqCategories' => helpdesk()->get('show_knowledge_base')
                ? FaqCategory::query()->public()->withCount(['faqs' => fn ($query) => $query->where('is_published', true)])->get()->where('faqs_count', '>', 0)
                : collect(),
        ]);
    }
}
