<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Article;

class ArticleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $article;
    /**
     * Create a new message instance.
     *
     * @return void
     */
   public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.article.new-article')
            ->with('article', $this->article);;
    }
}
