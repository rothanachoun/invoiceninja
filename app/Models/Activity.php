<?php namespace App\Models;

use Auth;
use Eloquent;

/**
 * Class Activity
 */
class Activity extends Eloquent
{
    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @param $query
     * @return mixed
     */
    public function scopeScope($query)
    {
        return $query->whereAccountId(Auth::user()->account_id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function contact()
    {
        return $this->belongsTo('App\Models\Contact')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function credit()
    {
        return $this->belongsTo('App\Models\Credit')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function payment()
    {
        return $this->belongsTo('App\Models\Payment')->withTrashed();
    }

    public function task()
    {
        return $this->belongsTo('App\Models\Task')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        $activityTypeId = $this->activity_type_id;
        $account = $this->account;
        $client = $this->client;
        $user = $this->user;
        $invoice = $this->invoice;
        $contactId = $this->contact_id;
        $payment = $this->payment;
        $credit = $this->credit;
        $isSystem = $this->is_system;

        /** @var Task $task */
        $task = $this->task;

        $data = [
            'client' => $client ? link_to($client->getRoute(), $client->getDisplayName()) : null,
            'user' => $isSystem ? '<i>' . trans('texts.system') . '</i>' : $user->getDisplayName(),
            'invoice' => $invoice ? link_to($invoice->getRoute(), $invoice->getDisplayName()) : null,
            'quote' => $invoice ? link_to($invoice->getRoute(), $invoice->getDisplayName()) : null,
            'contact' => $contactId ? $client->getDisplayName() : $user->getDisplayName(),
            'payment' => $payment ? $payment->transaction_reference : null,
            'payment_amount' => $payment ? $account->formatMoney($payment->amount, $payment) : null,
            'adjustment' => $this->adjustment ? $account->formatMoney($this->adjustment, $this) : asdf,
            'credit' => $credit ? $account->formatMoney($credit->amount, $client) : null,
            'task' => $task ? link_to($task->getRoute(), substr($task->description, 0, 30).'...') : null,
        ];

        return trans("texts.activity_{$activityTypeId}", $data);
    }
}
