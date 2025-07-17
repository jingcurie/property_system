<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class FileAttachmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectLine;
    public string $bodyText;
    public Collection $files;

    public function __construct(string $subject, string $body, Collection $files)
    {
        $this->subjectLine = $subject;
        $this->bodyText = $body;
        $this->files = $files;
    }

    public function build()
    {
        $email = $this->subject($this->subjectLine)
                      ->view('emails.file_attachment') // 替换成你自己的视图路径
                      ->with([
                          'body' => $this->bodyText,
                      ]);

        foreach ($this->files as $file) {
            $email->attach(storage_path('app/public/' . $file->path), [
                'as' => $file->filename,
                'mime' => $file->mime_type,
            ]);
        }

        return $email;
    }
}
