<?php

namespace App\Http\Controllers;

use App\Services\PassbookService;
use Illuminate\Http\Request;

class PassbookController extends Controller
{
    protected PassbookService $passbookService;

    public function __construct(PassbookService $passbookService)
    {
        $this->passbookService = $passbookService;
    }

    public function index()
    {
        return view('passbook.index', [
            'eventName' => 'The Beat Goes On',
            'location' => 'Moscone West',
            'dateTime' => '2013-04-15 @10:25'
        ]);
    }

    public function download()
    {
        $pkpassFile = $this->passbookService->createEventTicket(
            '1234567890',
            'The Beat Goes On',
            'The Beat Goes On',
            'Moscone West',
            '2013-04-15 @10:25',
            'resources/images/icon.png',
            'barcodeMessage'
        );

        return response()->download($pkpassFile, 'event-ticket.pkpass', [
            'Content-Type' => 'application/vnd.apple.pkpass',
            'Content-Disposition' => 'attachment; filename="event-ticket.pkpass"'
        ]);
    }
} 