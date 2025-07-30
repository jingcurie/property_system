<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocuSignWebhookController;

Route::post('/webhooks/docusign', [DocuSignWebhookController::class, 'handle']);