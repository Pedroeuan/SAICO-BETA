<?php

declare(strict_types=1);

return [
    'accepted'             => 'Полето :attribute мора да биде прифатено.',
    'accepted_if'          => ':Attribute мора да се прифати кога :other е :value.',
    'active_url'           => 'Полето :attribute не е валиден URL.',
    'after'                => 'Полето :attribute мора да биде датум после :date.',
    'after_or_equal'       => 'Полето :attribute мора да биде датум кој е после или еднаков на :date.',
    'alpha'                => 'Полето :attribute може да содржи само букви.',
    'alpha_dash'           => 'Полето :attribute може да содржи само букви, броеви, долна црта и тире.',
    'alpha_num'            => 'Полето :attribute може да содржи само букви и броеви.',
    'array'                => 'Полето :attribute мора да биде низа.',
    'ascii'                => ':Attribute мора да содржи само алфанумерички знаци и симболи од еден бајт.',
    'before'               => 'Полето :attribute мора да биде датум пред :date.',
    'before_or_equal'      => 'Полето :attribute мора да биде датум пред или еднаков на :date.',
    'between'              => [
        'array'   => 'Полето :attribute мора да има помеѓу :min - :max елементи.',
        'file'    => 'Полето :attribute мора да биде датотека со големина помеѓу :min и :max килобајти.',
        'numeric' => 'Полето :attribute мора да биде број помеѓу :min и :max.',
        'string'  => 'Полето :attribute мора да биде текст со должина помеѓу :min и :max карактери.',
    ],
    'boolean'              => 'Полето :attribute мора да има вредност вистинито (true) или невистинито (false).',
    'can'                  => 'Полето :attribute содржи неовластена вредност.',
    'confirmed'            => 'Полето :attribute не е потврдено.',
    'contains'             => 'The :attribute field is missing a required value.',
    'current_password'     => 'Лозинката е неточна.',
    'date'                 => 'Полето :attribute не е валиден датум.',
    'date_equals'          => 'Полето :attribute мора да биде датум еднаков на :date.',
    'date_format'          => 'Полето :attribute не одговара на форматот :format.',
    'decimal'              => ':Attribute-те мора да имаат :decimal децимални места.',
    'declined'             => ':Attribute-те мора да се одбијат.',
    'declined_if'          => ':Attribute мора да се одбие кога :other е :value.',
    'different'            => 'Полињата :attribute и :other треба да се различни.',
    'digits'               => 'Полето :attribute треба да има :digits цифри.',
    'digits_between'       => 'Полето :attribute треба да има помеѓу :min и :max цифри.',
    'dimensions'           => 'Полето :attribute има невалидни димензии.',
    'distinct'             => 'Полето :attribute има вредност која е дупликат.',
    'doesnt_end_with'      => ':Attribute-ката не може да завршува со едно од следниве: :values.',
    'doesnt_start_with'    => ':Attribute не смее да започнува со едно од следниве: :values.',
    'email'                => 'Полето :attribute не е во валиден формат.',
    'ends_with'            => 'Полето :attribute мора да завршува со една од следните вредности: :values.',
    'enum'                 => 'Избраните :attribute се неважечки.',
    'exists'               => 'Полето :attribute има вредност која веќе постои.',
    'extensions'           => 'Полето :attribute мора да има едно од следниве наставки: :values.',
    'file'                 => 'Полето :attribute мора да биде датотека.',
    'filled'               => 'Полето :attribute мора да има вредност.',
    'gt'                   => [
        'array'   => 'Полето :attribute мора да има повеке од :value елементи.',
        'file'    => 'Полето :attribute мора да биде датотека поголема од :value килобајти.',
        'numeric' => 'Полето :attribute мора да биде број поголем од :value.',
        'string'  => 'Полето :attribute мора да биде текст со повеќе од :value карактери.',
    ],
    'gte'                  => [
        'array'   => 'Полето :attribute мора да има :value елементи или повеќе.',
        'file'    => 'Полето :attribute мора да биде датотека поголема или еднаква на :value килобајти.',
        'numeric' => 'Полето :attribute мора да биде број поголем или еднаков на :value.',
        'string'  => 'Полето :attribute мора да биде текст со повеќе или еднаков на :value број на карактери.',
    ],
    'hex_color'            => 'Полето :attribute мора да биде валидна хексадецимална боја.',
    'image'                => 'Полето :attribute мора да биде слика.',
    'in'                   => 'Избраното поле :attribute е невалидно.',
    'in_array'             => 'Полето :attribute не содржи вредност која постои во :other.',
    'integer'              => 'Полето :attribute мора да биде цел број.',
    'ip'                   => 'Полето :attribute мора да биде валидна IP адреса.',
    'ipv4'                 => 'Полето :attribute мора да биде валидна IPv4 адреса.',
    'ipv6'                 => 'Полето :attribute мора да биде валидна IPv6 адреса.',
    'json'                 => 'Полето :attribute мора да биде валиден JSON објект.',
    'list'                 => 'Полето :attribute мора да биде листа.',
    'lowercase'            => ':Attribute-ката мора да биде мала.',
    'lt'                   => [
        'array'   => 'Полето :attribute мора да има помалку од :value елементи.',
        'file'    => 'Полето :attribute мора да биде датотека помала од :value килобајти.',
        'numeric' => 'Полето :attribute мора да биде број помал од :value.',
        'string'  => 'Полето :attribute мора да биде текст помал од :value број на карактери.',
    ],
    'lte'                  => [
        'array'   => 'Полето :attribute мора да има :value елементи или помалку.',
        'file'    => 'Полето :attribute мора да биде датотека помала или еднаква на :value килобајти.',
        'numeric' => 'Полето :attribute мора да биде број помал или еднаков на :value.',
        'string'  => 'Полето :attribute мора да биде текст со помалку или еднаков на :value број на карактери.',
    ],
    'mac_address'          => ':Attribute мора да биде валидна MAC адреса.',
    'max'                  => [
        'array'   => 'Полето :attribute не може да има повеќе од :max елементи.',
        'file'    => 'Полето :attribute мора да биде датотека не поголема од :max килобајти.',
        'numeric' => 'Полето :attribute мора да биде број не поголем од :max.',
        'string'  => 'Полето :attribute мора да има не повеќе од :max карактери.',
    ],
    'max_digits'           => ':Attribute-ката не смее да има повеќе од :max цифри.',
    'mimes'                => 'Полето :attribute мора да биде датотека од типот: :values.',
    'mimetypes'            => 'Полето :attribute мора да биде датотека од типот: :values.',
    'min'                  => [
        'array'   => 'Полето :attribute мора да има минимум :min елементи.',
        'file'    => 'Полето :attribute мора да биде датотека не помала од :min килобајти.',
        'numeric' => 'Полето :attribute мора да биде број не помал од :min.',
        'string'  => 'Полето :attribute мора да има не помалку од :min карактери.',
    ],
    'min_digits'           => ':Attribute-ката мора да има најмалку :min цифри.',
    'missing'              => 'Полето :attribute мора да недостасува.',
    'missing_if'           => 'Полето :attribute мора да недостасува кога :other е :value.',
    'missing_unless'       => 'Полето :attribute мора да недостасува освен ако :other не е :value.',
    'missing_with'         => 'Полето :attribute мора да недостасува кога има :values.',
    'missing_with_all'     => 'Полето :attribute мора да недостасува кога се присутни :values.',
    'multiple_of'          => 'Полето :attribute мора да биде повеќекратна вредност од :value',
    'not_in'               => 'Избраното поле :attribute е невалидно.',
    'not_regex'            => 'Полето :attribute има невалиден формат.',
    'numeric'              => 'Полето :attribute мора да биде број.',
    'password'             => [
        'letters'       => ':Attribute-ката мора да содржи најмалку една буква.',
        'mixed'         => ':Attribute мора да содржи најмалку една голема и една мала буква.',
        'numbers'       => ':Attribute-ката мора да содржи најмалку еден број.',
        'symbols'       => ':Attribute мора да содржи најмалку еден симбол.',
        'uncompromised' => 'Дадените :attribute се појавија во истекување на податоци. Изберете различни :attribute.',
    ],
    'present'              => 'Полето :attribute мора да биде присутно.',
    'present_if'           => 'Полето :attribute мора да биде присутно кога :other е :value.',
    'present_unless'       => 'Полето :attribute мора да биде присутно освен ако :other не е :value.',
    'present_with'         => 'Полето :attribute мора да биде присутно кога е присутно :values.',
    'present_with_all'     => 'Полето :attribute мора да биде присутно кога се присутни :values.',
    'prohibited'           => 'Во :attribute година полето е забрането.',
    'prohibited_if'        => 'На :attribute поле е забрането кога :other е :value.',
    'prohibited_unless'    => 'На :attribute поле е забранета освен ако не :other е во :values.',
    'prohibits'            => 'Полето :attribute забранува присуство на :other.',
    'regex'                => 'Полето :attribute има невалиден формат.',
    'required'             => 'Полето :attribute е задолжително.',
    'required_array_keys'  => 'Полето :attribute мора да содржи записи за: :values.',
    'required_if'          => 'Полето :attribute е задолжително кога :other е :value.',
    'required_if_accepted' => 'Полето :attribute е потребно кога ќе се прифати :other.',
    'required_if_declined' => 'The :attribute field is required when :other is declined.',
    'required_unless'      => 'Полето :attribute е задолжително освен кога :other е во :values.',
    'required_with'        => 'Полето :attribute е задолжително кога е внесено :values.',
    'required_with_all'    => 'Полето :attribute е задолжително кога :values се присутни.',
    'required_without'     => 'Полето :attribute е задолжително кога не е присутно :values.',
    'required_without_all' => 'Полето :attribute е задолжително кога ниту една вредност од следните: :values се присутни.',
    'same'                 => 'Полињата :attribute и :other треба да совпаѓаат.',
    'size'                 => [
        'array'   => 'Полето :attribute мора да биде низа со :size број на елементи.',
        'file'    => 'Полето :attribute мора да биде датотека со големина од :size килобајти.',
        'numeric' => 'Полето :attribute мора да биде број со вредност :size.',
        'string'  => 'Полето :attribute мора да биде текст со должина од :size број на карактери.',
    ],
    'starts_with'          => 'Полето :attribute мора да започнува со една од следните вредности: :values.',
    'string'               => 'Полето :attribute мора да биде текст.',
    'timezone'             => 'Полето :attribute мора да биде валидна временска зона.',
    'ulid'                 => ':Attribute мора да биде валиден ULID.',
    'unique'               => 'Полето :attribute веќе постои.',
    'uploaded'             => ':Attribute не е прикачен.',
    'uppercase'            => ':Attribute-ката мора да биде голема.',
    'url'                  => 'Полето :attribute не е во валиден формат.',
    'uuid'                 => 'Полето :attribute мора да биде валиден УУИД.',
    'attributes'           => [
        'address'                  => 'адреса',
        'affiliate_url'            => 'партнерска URL-адреса',
        'age'                      => 'години',
        'amount'                   => 'износ',
        'announcement'             => 'најава',
        'area'                     => 'област',
        'audience_prize'           => 'награда на публиката',
        'available'                => 'достапни',
        'birthday'                 => 'роденден',
        'body'                     => 'основен текст на порака',
        'city'                     => 'град',
        'compilation'              => 'компилација',
        'concept'                  => 'концепт',
        'conditions'               => 'Услови',
        'content'                  => 'содржина',
        'country'                  => 'држава',
        'cover'                    => 'покритие',
        'created_at'               => 'создадена во',
        'creator'                  => 'творец',
        'currency'                 => 'валута',
        'current_password'         => 'сегашна лозинка',
        'customer'                 => 'клиент',
        'date'                     => 'датум',
        'date_of_birth'            => 'Дата на раѓање',
        'dates'                    => 'датуми',
        'day'                      => 'ден',
        'deleted_at'               => 'избришано во',
        'description'              => 'опис',
        'display_type'             => 'тип на дисплеј',
        'district'                 => 'област',
        'duration'                 => 'времетраење',
        'email'                    => 'е-mail адреса',
        'excerpt'                  => 'извадок',
        'filter'                   => 'филтер',
        'finished_at'              => 'заврши во',
        'first_name'               => 'име',
        'gender'                   => 'пол',
        'grand_prize'              => 'grand prize',
        'group'                    => 'група',
        'hour'                     => 'час',
        'image'                    => 'слика',
        'image_desktop'            => 'слика на работната површина',
        'image_main'               => 'главната слика',
        'image_mobile'             => 'мобилна слика',
        'images'                   => 'слики',
        'is_audience_winner'       => 'е победник на публиката',
        'is_hidden'                => 'се крие',
        'is_subscribed'            => 'е претплатен',
        'is_visible'               => 'е видлива',
        'is_winner'                => 'е победник',
        'items'                    => 'предмети',
        'key'                      => 'клуч',
        'last_name'                => 'презиме',
        'lesson'                   => 'лекција',
        'line_address_1'           => 'адреса на линија 1',
        'line_address_2'           => 'адреса на линија 2',
        'login'                    => 'Логирај Се',
        'message'                  => 'порака',
        'middle_name'              => 'средно име',
        'minute'                   => 'минута',
        'mobile'                   => 'мобилен',
        'month'                    => 'месец',
        'name'                     => 'име',
        'national_code'            => 'национален код',
        'number'                   => 'број',
        'password'                 => 'лозинка',
        'password_confirmation'    => 'повтори ја лозинката',
        'phone'                    => 'телефон',
        'photo'                    => 'фотографија',
        'portfolio'                => 'портфолио',
        'postal_code'              => 'поштенски код',
        'preview'                  => 'преглед',
        'price'                    => 'цена',
        'product_id'               => 'ID на производот',
        'product_uid'              => 'UID на производот',
        'product_uuid'             => 'UUID на производот',
        'promo_code'               => 'промотивен код',
        'province'                 => 'провинција',
        'quantity'                 => 'квантитет',
        'reason'                   => 'причина',
        'recaptcha_response_field' => 'Поле за одговор recaptcha',
        'referee'                  => 'судија',
        'referees'                 => 'судиите',
        'reject_reason'            => 'отфрли разум',
        'remember'                 => 'запомнете',
        'restored_at'              => 'реставрирана во',
        'result_text_under_image'  => 'резултат текст под сликата',
        'role'                     => 'улога',
        'rule'                     => 'правило',
        'rules'                    => 'правила',
        'second'                   => 'секунда',
        'sex'                      => 'пол',
        'shipment'                 => 'пратката',
        'short_text'               => 'краток текст',
        'size'                     => 'големина',
        'skills'                   => 'вештини',
        'slug'                     => 'голтка',
        'specialization'           => 'специјализација',
        'started_at'               => 'започна во',
        'state'                    => 'држава',
        'status'                   => 'статус',
        'street'                   => 'улица',
        'student'                  => 'студент',
        'subject'                  => 'наслов',
        'tag'                      => 'ознака',
        'tags'                     => 'ознаки',
        'teacher'                  => 'наставник',
        'terms'                    => 'термини',
        'test_description'         => 'опис на тестот',
        'test_locale'              => 'локација за тестирање',
        'test_name'                => 'име на тестот',
        'text'                     => 'текст',
        'time'                     => 'време',
        'title'                    => 'наслов',
        'type'                     => 'тип',
        'updated_at'               => 'ажурирано на',
        'user'                     => 'корисник',
        'username'                 => 'корисничко име',
        'value'                    => 'вредност',
        'year'                     => 'година',
    ],
];
