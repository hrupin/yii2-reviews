<?php
namespace hrupin\reviews;

use Yii;
use yii\base\Component;

class Mailer extends Component
{
    public $viewPath = '@hrupin/reviews/views/mail';
    public $sender;
    public $mailerComponent;
    protected $commentSubject;
    protected $module;

    public function getCommentSubject()
    {
        if ($this->commentSubject == null) {
            $this->setCommentSubject(Yii::t('reviews', 'New reviews to {0}', Yii::$app->name));
        }
        return $this->commentSubject;
    }
    public function setCommentSubject($commentSubject)
    {
        $this->commentSubject = $commentSubject;
    }

    public function init()
    {
        $this->module = Yii::$app->getModule('blog');
        parent::init();
    }
    public function sendCommentMessage(Comment $comment)
    {
        return $this->sendMessage(
            $comment->email,
            $this->getCommentSubject(),
            'comment'
            //,
            //['user' => $user, 'module' => $this->module]
        );
    }

    protected function sendMessage($to, $subject, $view, $params = [])
    {
        $mailer = $this->mailerComponent === null ? Yii::$app->mailer : Yii::$app->get($this->mailerComponent);
        $mailer->viewPath = $this->viewPath;
        $mailer->getView()->theme = Yii::$app->view->theme;
        if ($this->sender === null) {
            $this->sender = isset(Yii::$app->params['adminEmail']) ?
                Yii::$app->params['adminEmail']
                : 'no-reply@example.com';
        }
        return $mailer->compose(['html' => $view, 'text' => 'text/' . $view], $params)
            ->setTo($to)
            ->setFrom($this->sender)
            ->setSubject($subject)
            ->send();
    }
}