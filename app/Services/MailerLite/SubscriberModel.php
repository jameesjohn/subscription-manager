<?php

namespace App\Services\MailerLite;

use Carbon\Carbon;

class SubscriberModel
{
    public string $name;
    public string $email;
    public string $country;
    public Carbon $dateSubscribed;
    public string $id = "";


    public function toMailerLiteArray()
    {
        return [
            'email' => $this->email,
            'fields' => [
                'name' => $this->name,
                'country' => $this->country
            ]
        ];
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'country' => $this->country,
            'date_subscribed' => $this->dateSubscribed->format('d/m/Y'),
            'time_subscribed' => $this->dateSubscribed->toTimeString()
        ];
    }

    private function setDateSubscribed(string $date)
    {
        $this->dateSubscribed = new Carbon($date);
    }

    public static function newFromArray(array $data): SubscriberModel
    {
        $model = new SubscriberModel();
        $model->email = $data['email'];
        $model->name = $data['name'];
        $model->country = $data['country'];

        return $model;
    }

    public static function newFromMailerLiteArray(array $data): SubscriberModel
    {
        $fields = $data['fields'];

        $model = new SubscriberModel();
        $model->id = $data['id'];
        $model->email = $data['email'];
        $model->name = $fields['name'] ?: '';
        $model->country = $fields['country'] ?: '';

        if ($data['subscribed_at']) {
            $model->setDateSubscribed($data['subscribed_at']);
        }

        return $model;
    }
}
