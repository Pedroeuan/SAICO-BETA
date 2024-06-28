<?php

declare(strict_types=1);

return [
    'accepted'             => 'Ko te :attribute me whakaae.',
    'accepted_if'          => 'Me whakaae te :attribute ina :other te :value.',
    'active_url'           => 'Ko te :attribute ehara i te URL whaimana.',
    'after'                => 'Me noho te :attribute i muri i te :date.',
    'after_or_equal'       => 'Ko te :attribute he ra i muri mai, he rite ranei ki te :date.',
    'alpha'                => 'Ko te :attribute me mau reta anake.',
    'alpha_dash'           => 'Ko te :attribute anake me mau reta, tau, pirihono me nga tohu.',
    'alpha_num'            => 'Ko te :attribute me mau reta me nga nama anake.',
    'array'                => 'Ko te :attribute me he huinga.',
    'ascii'                => 'Ko te āpure :attribute me whai tohu-paita-a-paita anake me nga tohu.',
    'before'               => 'Ko te :attribute me he ra i mua i te :date.',
    'before_or_equal'      => 'Ko te :attribute he ra i mua atu, kia rite ranei ki te :date.',
    'between'              => [
        'array'   => 'Ko te :attribute me :min ki te :max nga taonga.',
        'file'    => 'Me noho te :attribute ki waenga i te :min me te :max kiropaita.',
        'numeric' => 'Me noho te :attribute ki waenga i te :min me te :max.',
        'string'  => 'Me noho te :attribute i waenga i te :min me te :max nga tohu.',
    ],
    'boolean'              => 'Me pono, he teka ranei te mara :attribute.',
    'can'                  => 'Kei roto i te mara :attribute tetahi uara kore mana.',
    'confirmed'            => 'Ko te whakapumautanga :attribute kaore e rite.',
    'contains'             => 'The :attribute field is missing a required value.',
    'current_password'     => 'He he te kupuhipa.',
    'date'                 => 'Ko te :attribute ehara i te ra whaimana.',
    'date_equals'          => 'Ko te :attribute he ra rite ki te :date.',
    'date_format'          => 'Ko te :attribute kaore e rite ki te whakatakotoranga :format.',
    'decimal'              => 'Me :decimal nga waahi ira o te mara :attribute.',
    'declined'             => 'Me whakakore te :attribute.',
    'declined_if'          => 'Me whakaheke te :attribute ina he :value te :other.',
    'different'            => 'Me rereke te :attribute me te :other.',
    'digits'               => 'Ko te :attribute me :digits mati.',
    'digits_between'       => 'Me noho te :attribute i waenga i te :min me te :max mati.',
    'dimensions'           => 'He muhu te ahua o te :attribute.',
    'distinct'             => 'He uara tārite te āpure :attribute.',
    'doesnt_end_with'      => 'Kaua e mutu te mara :attribute ki tetahi o enei e whai ake nei: :values.',
    'doesnt_start_with'    => 'Kaua e timata te mara :attribute ki tetahi o enei e whai ake nei: :values.',
    'email'                => 'Ko te :attribute he wahitau imeera whaimana.',
    'ends_with'            => 'Me mutu te :attribute ki tetahi o enei e whai ake nei: :values.',
    'enum'                 => 'Ko te :attribute kua tohua he muhu.',
    'exists'               => 'Ko te :attribute kua tohua he muhu.',
    'extensions'           => 'Ko te mara :attribute me whai tetahi o nga toronga e whai ake nei: :values.',
    'file'                 => 'Ko te :attribute he konae.',
    'filled'               => 'Me whai uara te mara :attribute.',
    'gt'                   => [
        'array'   => 'Ko te :attribute me nui ake i te :value nga taonga.',
        'file'    => 'Ko te :attribute me nui ake i te :value kiropaita.',
        'numeric' => 'Ko te :attribute me nui ake i te :value.',
        'string'  => 'Ko te :attribute me nui ake i te :value nga tohu.',
    ],
    'gte'                  => [
        'array'   => 'Ko te :attribute me :value nga taonga, neke atu ranei.',
        'file'    => 'Me nui ake te :attribute ki te :value kiropaita ranei.',
        'numeric' => 'Me nui ake te :attribute ki te :value ranei.',
        'string'  => 'Ko te :attribute me nui ake i te rite ranei ki nga tohu :value.',
    ],
    'hex_color'            => 'Ko te āpure :attribute he tae hautekaumāono whaimana.',
    'image'                => 'Me ahua te :attribute.',
    'in'                   => 'Ko te :attribute kua tohua he muhu.',
    'in_array'             => 'Karekau te mara :attribute i te :other.',
    'integer'              => 'Me noho tauoti te :attribute.',
    'ip'                   => 'Ko te :attribute he wahitau IP whaimana.',
    'ipv4'                 => 'Ko te :attribute he wahitau IPv4 whaimana.',
    'ipv6'                 => 'Ko te :attribute he wahitau IPv6 whaimana.',
    'json'                 => 'Ko te :attribute he aho JSON whaimana.',
    'list'                 => 'Ko te mara :attribute me he rarangi.',
    'lowercase'            => 'Me pūriki te āpure :attribute.',
    'lt'                   => [
        'array'   => 'Ko te :attribute me iti ake i te :value nga taonga.',
        'file'    => 'Ko te :attribute me iti iho i te :value kiropaita.',
        'numeric' => 'Ko te :attribute me iti iho i te :value.',
        'string'  => 'Ko te :attribute me iti ake i te :value nga tohu.',
    ],
    'lte'                  => [
        'array'   => 'Ko te :attribute me kaua e neke ake i te :value nga taonga.',
        'file'    => 'Ko te :attribute me iti iho i te rite ranei ki te :value kiropaita.',
        'numeric' => 'Ko te :attribute me iti iho i te rite ranei ki te :value.',
        'string'  => 'Ko te :attribute me iti ake i te rite ranei ki te :value nga tohu.',
    ],
    'mac_address'          => 'Ko te :attribute he wahitau MAC whaimana.',
    'max'                  => [
        'array'   => 'Ko te :attribute me kaua e neke ake i te :max nga taonga.',
        'file'    => 'Ko te :attribute me kaua e nui ake i te :max kiropaita.',
        'numeric' => 'Ko te :attribute me kaua e nui ake i te :max.',
        'string'  => 'Ko te :attribute me kaua e nui ake i te :max nga tohu.',
    ],
    'max_digits'           => 'Ko te mara :attribute kaua e neke ake i te :max mati.',
    'mimes'                => 'Ko te :attribute me he momo momo: :values.',
    'mimetypes'            => 'Ko te :attribute me he momo momo: :values.',
    'min'                  => [
        'array'   => 'Ko te :attribute me :min nga taonga.',
        'file'    => 'Ko te :attribute me :min kiropaita neke atu.',
        'numeric' => 'Ko te :attribute me :min neke atu.',
        'string'  => 'Ko te :attribute me :min neke atu nga tohu.',
    ],
    'min_digits'           => 'Me :min neke atu nga mati o te mara :attribute.',
    'missing'              => 'Me ngaro te mara :attribute.',
    'missing_if'           => 'Me ngaro te mara :attribute ina :other te :value.',
    'missing_unless'       => 'Me ngaro te mara :attribute ki te kore e :other te :value.',
    'missing_with'         => 'Me ngaro te mara :attribute ina :values kei reira.',
    'missing_with_all'     => 'Me ngaro te mara :attribute ina :values kei reira.',
    'multiple_of'          => 'Ko te :attribute he taurea o te :value.',
    'not_in'               => 'Ko te :attribute kua tohua he muhu.',
    'not_regex'            => 'He muhu te whakatakotoranga :attribute.',
    'numeric'              => 'Ko te :attribute he tau.',
    'password'             => [
        'letters'       => 'Ko te mara :attribute me uru kia kotahi te reta.',
        'mixed'         => 'Me mau te āpure :attribute kia kotahi te pūmatua me te pū iti.',
        'numbers'       => 'Ko te mara :attribute me uru kia kotahi neke atu te tau.',
        'symbols'       => 'Ko te mara :attribute me uru kia kotahi te tohu.',
        'uncompromised' => 'Ko te :attribute kua hoatu kua puta i roto i te rerenga raraunga. Kōwhiria he :attribute kē.',
    ],
    'present'              => 'Me noho te mara :attribute.',
    'present_if'           => 'Me noho te mara :attribute ina :other te :value.',
    'present_unless'       => 'Me noho te mara :attribute ki te kore e :other te :value.',
    'present_with'         => 'Me noho te mara :attribute ina :values kei reira.',
    'present_with_all'     => 'Me noho te mara :attribute ina :values kei reira.',
    'prohibited'           => 'Kua rāhuitia te mara :attribute.',
    'prohibited_if'        => 'Ka rahuitia te mara :attribute ina :other he :value.',
    'prohibited_unless'    => 'Ka rahuitia te mara :attribute ki te kore e :other kei roto i te :values.',
    'prohibits'            => 'Ko te mara :attribute ka aukati i te :other kia tae mai.',
    'regex'                => 'He muhu te whakatakotoranga :attribute.',
    'required'             => 'Ko te mara :attribute e hiahiatia ana.',
    'required_array_keys'  => 'Me whakauru te mara :attribute mo: :values.',
    'required_if'          => 'Me :attribute te mara ina :other te :value.',
    'required_if_accepted' => 'Ka hiahiatia te mara :attribute ina whakaaetia te :other.',
    'required_if_declined' => 'The :attribute field is required when :other is declined.',
    'required_unless'      => 'Me :attribute te mara ki te kore e :other kei roto i te :values.',
    'required_with'        => 'Ko te mara :attribute e hiahiatia ana ina :values kei reira.',
    'required_with_all'    => 'Ko te mara :attribute e hiahiatia ana ina :values kei reira.',
    'required_without'     => 'Me :attribute te mara ina karekau te :values.',
    'required_without_all' => 'Ka hiahiatia te mara :attribute ina karekau he :values.',
    'same'                 => 'Me ōrite te :attribute me te :other.',
    'size'                 => [
        'array'   => 'Me :size nga mea kei roto i te :attribute.',
        'file'    => 'Ko te :attribute me :size kiropaita.',
        'numeric' => 'Me :size te :attribute.',
        'string'  => 'Ko te :attribute me :size nga tohu.',
    ],
    'starts_with'          => 'Me timata te :attribute ki tetahi o enei e whai ake nei: :values.',
    'string'               => 'Ko te :attribute me he aho.',
    'timezone'             => 'Ko te :attribute he rohe waahi whaimana.',
    'ulid'                 => 'Ko te mara :attribute he ULID whaimana.',
    'unique'               => 'Kua tangohia kē te :attribute.',
    'uploaded'             => 'I rahua te :attribute ki te tuku ake.',
    'uppercase'            => 'Me pūmatua te āpure :attribute.',
    'url'                  => 'Me whai URL tika te :attribute.',
    'uuid'                 => 'Ko te :attribute he UUID whaimana.',
    'attributes'           => [
        'address'                  => 'wāhi noho',
        'affiliate_url'            => 'hononga URL',
        'age'                      => 'tau',
        'amount'                   => 'nui',
        'announcement'             => 'panui',
        'area'                     => 'rohe',
        'audience_prize'           => 'taonga whakarongo',
        'available'                => 'e wātea ana',
        'birthday'                 => 'huritau',
        'body'                     => 'tinana',
        'city'                     => 'pa',
        'compilation'              => 'whakahiato',
        'concept'                  => 'ariā',
        'conditions'               => 'tikanga',
        'content'                  => 'ihirangi',
        'country'                  => 'whenua',
        'cover'                    => 'uhi',
        'created_at'               => 'hanga i',
        'creator'                  => 'kaihanga',
        'currency'                 => 'moni',
        'current_password'         => 'kupuhipa o nāianei',
        'customer'                 => 'kaihoko',
        'date'                     => 'rā',
        'date_of_birth'            => 'ra whanau',
        'dates'                    => 'rā',
        'day'                      => 'rā',
        'deleted_at'               => 'kua mukua i',
        'description'              => 'whakaahuatanga',
        'display_type'             => 'momo whakaatu',
        'district'                 => 'rohe',
        'duration'                 => 'roanga',
        'email'                    => 'īmēra',
        'excerpt'                  => 'wahanga',
        'filter'                   => 'tātari',
        'finished_at'              => 'oti i',
        'first_name'               => 'ingoa tuatahi',
        'gender'                   => 'ira tangata',
        'grand_prize'              => 'taonga nui',
        'group'                    => 'roopu',
        'hour'                     => 'haora',
        'image'                    => 'whakaahua',
        'image_desktop'            => 'whakaahua papamahi',
        'image_main'               => 'whakaahua matua',
        'image_mobile'             => 'whakaahua pūkoro',
        'images'                   => 'whakaahua',
        'is_audience_winner'       => 'he toa te hunga whakarongo',
        'is_hidden'                => 'kei te huna',
        'is_subscribed'            => 'kua ohauru',
        'is_visible'               => 'ka kitea',
        'is_winner'                => 'he toa',
        'items'                    => 'tūemi',
        'key'                      => 'kī',
        'last_name'                => 'ingoa whanau',
        'lesson'                   => 'akoranga',
        'line_address_1'           => 'wāhitau raina 1',
        'line_address_2'           => 'wāhitau raina 2',
        'login'                    => 'takiuru',
        'message'                  => 'karere',
        'middle_name'              => 'ingoa waenganui',
        'minute'                   => 'meneti',
        'mobile'                   => 'pūkoro',
        'month'                    => 'marama',
        'name'                     => 'ingoa',
        'national_code'            => 'waehere motu',
        'number'                   => 'tau',
        'password'                 => 'kupuhipa',
        'password_confirmation'    => 'whakaū kupuhipa',
        'phone'                    => 'waea',
        'photo'                    => 'whakaahua',
        'portfolio'                => 'kōpaki',
        'postal_code'              => 'waehere poutāpeta',
        'preview'                  => 'arokite',
        'price'                    => 'utu',
        'product_id'               => 'ID hua',
        'product_uid'              => 'hua UID',
        'product_uuid'             => 'hua UUID',
        'promo_code'               => 'waehere whakatairanga',
        'province'                 => 'kawanatanga',
        'quantity'                 => 'rahinga',
        'reason'                   => 'take',
        'recaptcha_response_field' => 'recaptcha whakautu mara',
        'referee'                  => 'kaiwawao',
        'referees'                 => 'kaiwawao',
        'reject_reason'            => 'whakakahore take',
        'remember'                 => 'mahara',
        'restored_at'              => 'whakahokia mai i',
        'result_text_under_image'  => 'hua kuputuhi i raro i te ahua',
        'role'                     => 'tūranga',
        'rule'                     => 'ture',
        'rules'                    => 'ture',
        'second'                   => 'tuarua',
        'sex'                      => 'sex',
        'shipment'                 => 'tukunga',
        'short_text'               => 'kupu poto',
        'size'                     => 'rahi',
        'skills'                   => 'pūkenga',
        'slug'                     => 'paraka',
        'specialization'           => 'tohungatanga',
        'started_at'               => 'i timata i',
        'state'                    => 'āhua',
        'status'                   => 'tūnga',
        'street'                   => 'tiriti',
        'student'                  => 'tauira',
        'subject'                  => 'kaupapa',
        'tag'                      => 'tohu',
        'tags'                     => 'tūtohu',
        'teacher'                  => 'kaiako',
        'terms'                    => 'tikanga',
        'test_description'         => 'whakaahuatanga whakamatautau',
        'test_locale'              => 'waahi whakamatautau',
        'test_name'                => 'ingoa whakamatautau',
        'text'                     => 'kuputuhi',
        'time'                     => 'wā',
        'title'                    => 'taitara',
        'type'                     => 'momo',
        'updated_at'               => 'whakahoutia i',
        'user'                     => 'kaiwhakamahi',
        'username'                 => 'ingoa kaiwhakamahi',
        'value'                    => 'uara',
        'year'                     => 'tau',
    ],
];
