<?php

declare(strict_types=1);

return [
    'accepted'             => ':attribute అంగీకరించాలి.',
    'accepted_if'          => ':other :value అయినప్పుడు :attribute అంగీకరించాలి.',
    'active_url'           => ':attribute చెల్లుబాటు అయ్యే URL కాదు.',
    'after'                => ':attribute తప్పనిసరిగా :date తర్వాత తేదీ అయి ఉండాలి.',
    'after_or_equal'       => ':attribute తప్పనిసరిగా :date తర్వాత లేదా సమానమైన తేదీగా ఉండాలి.',
    'alpha'                => ':attributeలో అక్షరాలు మాత్రమే ఉండాలి.',
    'alpha_dash'           => ':attribute తప్పనిసరిగా అక్షరాలు, సంఖ్యలు, డాష్‌లు మరియు అండర్‌స్కోర్‌లను మాత్రమే కలిగి ఉండాలి.',
    'alpha_num'            => ':attribute తప్పనిసరిగా అక్షరాలు మరియు సంఖ్యలను మాత్రమే కలిగి ఉండాలి.',
    'array'                => ':attribute తప్పనిసరిగా శ్రేణి అయి ఉండాలి.',
    'ascii'                => ':attribute ఫీల్డ్ తప్పనిసరిగా సింగిల్-బైట్ ఆల్ఫాన్యూమరిక్ అక్షరాలు మరియు చిహ్నాలను మాత్రమే కలిగి ఉండాలి.',
    'before'               => ':attribute తప్పనిసరిగా :dateకి ముందు తేదీ అయి ఉండాలి.',
    'before_or_equal'      => ':attribute తప్పనిసరిగా :dateకి ముందు లేదా సమానమైన తేదీ అయి ఉండాలి.',
    'between'              => [
        'array'   => ':attribute తప్పనిసరిగా :min మరియు :max అంశాలను కలిగి ఉండాలి.',
        'file'    => ':attribute తప్పనిసరిగా :min మరియు :max కిలోబైట్ల మధ్య ఉండాలి.',
        'numeric' => ':attribute తప్పనిసరిగా :min మరియు :max మధ్య ఉండాలి.',
        'string'  => ':attribute తప్పనిసరిగా :min మరియు :max అక్షరాల మధ్య ఉండాలి.',
    ],
    'boolean'              => ':attribute ఫీల్డ్ తప్పక నిజం లేదా తప్పు అయి ఉండాలి.',
    'can'                  => ':attribute ఫీల్డ్ అనధికార విలువను కలిగి ఉంది.',
    'confirmed'            => ':attribute నిర్ధారణ సరిపోలలేదు.',
    'contains'             => 'The :attribute field is missing a required value.',
    'current_password'     => 'పాస్వర్డ్ తప్పు.',
    'date'                 => ':attribute చెల్లుబాటు అయ్యే తేదీ కాదు.',
    'date_equals'          => ':attribute తప్పనిసరిగా :dateకి సమానమైన తేదీగా ఉండాలి.',
    'date_format'          => ':attribute ఫార్మాట్ :formatతో సరిపోలడం లేదు.',
    'decimal'              => ':attribute ఫీల్డ్ తప్పనిసరిగా :decimal దశాంశ స్థానాలను కలిగి ఉండాలి.',
    'declined'             => ':attribute తప్పనిసరిగా తిరస్కరించబడాలి.',
    'declined_if'          => ':other :value అయినప్పుడు :attribute తప్పనిసరిగా తిరస్కరించబడాలి.',
    'different'            => ':attribute మరియు :other వేర్వేరుగా ఉండాలి.',
    'digits'               => ':attribute తప్పనిసరిగా :digits అంకెలు ఉండాలి.',
    'digits_between'       => ':attribute తప్పనిసరిగా :min మరియు :max అంకెల మధ్య ఉండాలి.',
    'dimensions'           => ':attribute చెల్లని చిత్ర పరిమాణాలను కలిగి ఉంది.',
    'distinct'             => ':attribute ఫీల్డ్ నకిలీ విలువను కలిగి ఉంది.',
    'doesnt_end_with'      => ':attribute ఫీల్డ్ కింది వాటిలో ఒకదానితో ముగియకూడదు: :values.',
    'doesnt_start_with'    => ':attribute ఫీల్డ్ కింది వాటిలో ఒకదానితో ప్రారంభం కాకూడదు: :values.',
    'email'                => ':attribute తప్పక చెల్లుబాటు అయ్యే ఇమెయిల్ చిరునామా అయి ఉండాలి.',
    'ends_with'            => ':attribute కింది వాటిలో ఒకదానితో ముగియాలి: :values.',
    'enum'                 => 'ఎంచుకున్న :attribute చెల్లనివి.',
    'exists'               => 'ఎంచుకున్న :attribute చెల్లనివి.',
    'extensions'           => ':attribute ఫీల్డ్ తప్పనిసరిగా కింది పొడిగింపులలో ఒకదాన్ని కలిగి ఉండాలి: :values.',
    'file'                 => ':attribute తప్పనిసరిగా ఫైల్ అయి ఉండాలి.',
    'filled'               => ':attribute ఫీల్డ్ తప్పనిసరిగా విలువను కలిగి ఉండాలి.',
    'gt'                   => [
        'array'   => ':attribute తప్పనిసరిగా :value కంటే ఎక్కువ అంశాలను కలిగి ఉండాలి.',
        'file'    => ':attribute తప్పనిసరిగా :value కిలోబైట్‌ల కంటే ఎక్కువగా ఉండాలి.',
        'numeric' => ':attribute తప్పనిసరిగా :value కంటే ఎక్కువగా ఉండాలి.',
        'string'  => ':attribute తప్పనిసరిగా :value అక్షరాల కంటే ఎక్కువగా ఉండాలి.',
    ],
    'gte'                  => [
        'array'   => ':attribute తప్పనిసరిగా :value లేదా అంతకంటే ఎక్కువ అంశాలను కలిగి ఉండాలి.',
        'file'    => ':attribute తప్పనిసరిగా :value కిలోబైట్‌ల కంటే ఎక్కువగా లేదా సమానంగా ఉండాలి.',
        'numeric' => ':attribute తప్పనిసరిగా :value కంటే ఎక్కువగా లేదా సమానంగా ఉండాలి.',
        'string'  => ':attribute తప్పనిసరిగా :value అక్షరాల కంటే ఎక్కువగా లేదా సమానంగా ఉండాలి.',
    ],
    'hex_color'            => ':attribute ఫీల్డ్ తప్పనిసరిగా చెల్లుబాటు అయ్యే హెక్సాడెసిమల్ రంగు అయి ఉండాలి.',
    'image'                => ':attribute తప్పనిసరిగా చిత్రం అయి ఉండాలి.',
    'in'                   => 'ఎంచుకున్న :attribute చెల్లనివి.',
    'in_array'             => ':attribute ఫీల్డ్ :otherలో లేదు.',
    'integer'              => ':attribute తప్పనిసరిగా పూర్ణాంకం అయి ఉండాలి.',
    'ip'                   => ':attribute తప్పనిసరిగా చెల్లుబాటు అయ్యే IP చిరునామా అయి ఉండాలి.',
    'ipv4'                 => ':attribute తప్పనిసరిగా చెల్లుబాటు అయ్యే IPv4 చిరునామా అయి ఉండాలి.',
    'ipv6'                 => ':attribute తప్పనిసరిగా చెల్లుబాటు అయ్యే IPv6 చిరునామా అయి ఉండాలి.',
    'json'                 => ':attribute తప్పనిసరిగా చెల్లుబాటు అయ్యే JSON స్ట్రింగ్ అయి ఉండాలి.',
    'list'                 => ':attribute ఫీల్డ్ తప్పనిసరిగా జాబితా అయి ఉండాలి.',
    'lowercase'            => ':attribute ఫీల్డ్ తప్పనిసరిగా చిన్న అక్షరంగా ఉండాలి.',
    'lt'                   => [
        'array'   => ':attribute తప్పనిసరిగా :value కంటే తక్కువ అంశాలను కలిగి ఉండాలి.',
        'file'    => ':attribute తప్పనిసరిగా :value కిలోబైట్ల కంటే తక్కువగా ఉండాలి.',
        'numeric' => ':attribute తప్పనిసరిగా :value కంటే తక్కువగా ఉండాలి.',
        'string'  => ':attribute తప్పనిసరిగా :value అక్షరాల కంటే తక్కువగా ఉండాలి.',
    ],
    'lte'                  => [
        'array'   => ':attributeలో :value కంటే ఎక్కువ అంశాలు ఉండకూడదు.',
        'file'    => ':attribute తప్పనిసరిగా :value కిలోబైట్‌ల కంటే తక్కువగా లేదా సమానంగా ఉండాలి.',
        'numeric' => ':attribute తప్పనిసరిగా :value కంటే తక్కువగా లేదా సమానంగా ఉండాలి.',
        'string'  => ':attribute తప్పనిసరిగా :value అక్షరాల కంటే తక్కువగా లేదా సమానంగా ఉండాలి.',
    ],
    'mac_address'          => ':attribute తప్పక చెల్లుబాటు అయ్యే MAC చిరునామా అయి ఉండాలి.',
    'max'                  => [
        'array'   => ':attributeలో :max కంటే ఎక్కువ అంశాలు ఉండకూడదు.',
        'file'    => ':attribute :max కిలోబైట్‌ల కంటే ఎక్కువగా ఉండకూడదు.',
        'numeric' => ':attribute :max కంటే ఎక్కువ ఉండకూడదు.',
        'string'  => ':attribute :max అక్షరాల కంటే ఎక్కువ ఉండకూడదు.',
    ],
    'max_digits'           => ':attribute ఫీల్డ్ తప్పనిసరిగా :max అంకెల కంటే ఎక్కువ ఉండకూడదు.',
    'mimes'                => ':attribute తప్పనిసరిగా ఫైల్ రకం అయి ఉండాలి: :values.',
    'mimetypes'            => ':attribute తప్పనిసరిగా ఫైల్ రకం అయి ఉండాలి: :values.',
    'min'                  => [
        'array'   => ':attribute తప్పనిసరిగా కనీసం :min అంశాలను కలిగి ఉండాలి.',
        'file'    => ':attribute తప్పనిసరిగా కనీసం :min కిలోబైట్‌లు ఉండాలి.',
        'numeric' => ':attribute తప్పనిసరిగా కనీసం :min ఉండాలి.',
        'string'  => ':attribute తప్పనిసరిగా కనీసం :min అక్షరాలు ఉండాలి.',
    ],
    'min_digits'           => ':attribute ఫీల్డ్ తప్పనిసరిగా కనీసం :min అంకెలను కలిగి ఉండాలి.',
    'missing'              => ':attribute ఫీల్డ్ తప్పక లేదు.',
    'missing_if'           => ':other :value అయినప్పుడు :attribute ఫీల్డ్ తప్పక మిస్ అయి ఉండాలి.',
    'missing_unless'       => ':other :value అయితే తప్ప :attribute ఫీల్డ్ తప్పక తప్పదు.',
    'missing_with'         => ':values ఉన్నప్పుడు :attribute ఫీల్డ్ తప్పక తప్పదు.',
    'missing_with_all'     => ':values ఉన్నపుడు :attribute ఫీల్డ్ తప్పక తప్పదు.',
    'multiple_of'          => ':attribute తప్పనిసరిగా :valueకి గుణకారం అయి ఉండాలి.',
    'not_in'               => 'ఎంచుకున్న :attribute చెల్లనివి.',
    'not_regex'            => ':attribute ఫార్మాట్ చెల్లదు.',
    'numeric'              => ':attribute తప్పనిసరిగా సంఖ్య అయి ఉండాలి.',
    'password'             => [
        'letters'       => ':attribute ఫీల్డ్ తప్పనిసరిగా కనీసం ఒక అక్షరాన్ని కలిగి ఉండాలి.',
        'mixed'         => ':attribute ఫీల్డ్ తప్పనిసరిగా కనీసం ఒక పెద్ద అక్షరాన్ని మరియు ఒక చిన్న అక్షరాన్ని కలిగి ఉండాలి.',
        'numbers'       => ':attribute ఫీల్డ్ తప్పనిసరిగా కనీసం ఒక సంఖ్యను కలిగి ఉండాలి.',
        'symbols'       => ':attribute ఫీల్డ్ తప్పనిసరిగా కనీసం ఒక చిహ్నాన్ని కలిగి ఉండాలి.',
        'uncompromised' => 'ఇచ్చిన :attribute డేటా లీక్‌లో కనిపించింది. దయచేసి వేరే :attributeని ఎంచుకోండి.',
    ],
    'present'              => ':attribute ఫీల్డ్ తప్పనిసరిగా ఉండాలి.',
    'present_if'           => ':other :value అయినప్పుడు :attribute ఫీల్డ్ తప్పనిసరిగా ఉండాలి.',
    'present_unless'       => ':other :value అయితే తప్ప :attribute ఫీల్డ్ తప్పనిసరిగా ఉండాలి.',
    'present_with'         => ':values ఉన్నప్పుడు :attribute ఫీల్డ్ తప్పనిసరిగా ఉండాలి.',
    'present_with_all'     => ':values ఉన్నపుడు :attribute ఫీల్డ్ తప్పనిసరిగా ఉండాలి.',
    'prohibited'           => ':attribute ఫీల్డ్ నిషేధించబడింది.',
    'prohibited_if'        => ':other :value అయినప్పుడు :attribute ఫీల్డ్ నిషేధించబడింది.',
    'prohibited_unless'    => ':valuesలో :other ఉంటే తప్ప :attribute ఫీల్డ్ నిషేధించబడింది.',
    'prohibits'            => ':attribute ఫీల్డ్ :other ఉనికిని నిషేధిస్తుంది.',
    'regex'                => ':attribute ఫార్మాట్ చెల్లదు.',
    'required'             => ':attribute ఫీల్డ్ అవసరం.',
    'required_array_keys'  => ':attribute ఫీల్డ్ తప్పనిసరిగా దీని కోసం ఎంట్రీలను కలిగి ఉండాలి: :values.',
    'required_if'          => ':other :value అయినప్పుడు :attribute ఫీల్డ్ అవసరం.',
    'required_if_accepted' => ':other ఆమోదించబడినప్పుడు :attribute ఫీల్డ్ అవసరం.',
    'required_if_declined' => 'The :attribute field is required when :other is declined.',
    'required_unless'      => ':valuesలో :other ఉంటే తప్ప :attribute ఫీల్డ్ అవసరం.',
    'required_with'        => ':values ఉన్నప్పుడు :attribute ఫీల్డ్ అవసరం.',
    'required_with_all'    => ':values ఉన్నప్పుడు :attribute ఫీల్డ్ అవసరం.',
    'required_without'     => ':values లేనప్పుడు :attribute ఫీల్డ్ అవసరం.',
    'required_without_all' => ':valuesలో ఏదీ లేనప్పుడు :attribute ఫీల్డ్ అవసరం.',
    'same'                 => ':attribute మరియు :other తప్పనిసరిగా సరిపోలాలి.',
    'size'                 => [
        'array'   => ':attribute తప్పనిసరిగా :size అంశాలను కలిగి ఉండాలి.',
        'file'    => ':attribute తప్పనిసరిగా :size కిలోబైట్లు ఉండాలి.',
        'numeric' => ':attribute తప్పనిసరిగా :size అయి ఉండాలి.',
        'string'  => ':attribute తప్పనిసరిగా :size అక్షరాలు ఉండాలి.',
    ],
    'starts_with'          => ':attribute తప్పనిసరిగా కింది వాటిలో ఒకదానితో ప్రారంభం కావాలి: :values.',
    'string'               => ':attribute తప్పనిసరిగా స్ట్రింగ్ అయి ఉండాలి.',
    'timezone'             => ':attribute తప్పనిసరిగా చెల్లుబాటు అయ్యే టైమ్‌జోన్ అయి ఉండాలి.',
    'ulid'                 => ':attribute ఫీల్డ్ తప్పనిసరిగా చెల్లుబాటు అయ్యే ULID అయి ఉండాలి.',
    'unique'               => ':attribute ఇప్పటికే తీసుకోబడింది.',
    'uploaded'             => ':attribute అప్‌లోడ్ చేయడంలో విఫలమయ్యాయి.',
    'uppercase'            => ':attribute ఫీల్డ్ తప్పనిసరిగా పెద్ద అక్షరంతో ఉండాలి.',
    'url'                  => ':attribute తప్పనిసరిగా చెల్లుబాటు అయ్యే URL అయి ఉండాలి.',
    'uuid'                 => ':attribute తప్పనిసరిగా చెల్లుబాటు అయ్యే UUID అయి ఉండాలి.',
    'attributes'           => [
        'address'                  => 'చిరునామా',
        'affiliate_url'            => 'అనుబంధ URL',
        'age'                      => 'వయస్సు',
        'amount'                   => 'మొత్తం',
        'announcement'             => 'ప్రకటన',
        'area'                     => 'ప్రాంతం',
        'audience_prize'           => 'ప్రేక్షకుల బహుమతి',
        'available'                => 'అందుబాటులో',
        'birthday'                 => 'పుట్టినరోజు',
        'body'                     => 'శరీరం',
        'city'                     => 'నగరం',
        'compilation'              => 'సంగ్రహం',
        'concept'                  => 'భావన',
        'conditions'               => 'పరిస్థితులు',
        'content'                  => 'విషయము',
        'country'                  => 'దేశం',
        'cover'                    => 'కవర్',
        'created_at'               => 'వద్ద సృష్టించబడింది',
        'creator'                  => 'సృష్టికర్త',
        'currency'                 => 'కరెన్సీ',
        'current_password'         => 'ప్రస్తుత పాస్వర్డ్',
        'customer'                 => 'వినియోగదారుడు',
        'date'                     => 'తేదీ',
        'date_of_birth'            => 'పుట్టిన తేది',
        'dates'                    => 'తేదీలు',
        'day'                      => 'రోజు',
        'deleted_at'               => 'వద్ద తొలగించబడింది',
        'description'              => 'వివరణ',
        'display_type'             => 'ప్రదర్శన రకం',
        'district'                 => 'జిల్లా',
        'duration'                 => 'వ్యవధి',
        'email'                    => 'ఇమెయిల్',
        'excerpt'                  => 'సారాంశం',
        'filter'                   => 'వడపోత',
        'finished_at'              => 'వద్ద ముగిసింది',
        'first_name'               => 'మొదటి పేరు',
        'gender'                   => 'లింగం',
        'grand_prize'              => 'గొప్ప బహుమతి',
        'group'                    => 'సమూహం',
        'hour'                     => 'గంట',
        'image'                    => 'చిత్రం',
        'image_desktop'            => 'డెస్క్‌టాప్ చిత్రం',
        'image_main'               => 'ప్రధాన చిత్రం',
        'image_mobile'             => 'మొబైల్ చిత్రం',
        'images'                   => 'చిత్రాలు',
        'is_audience_winner'       => 'ప్రేక్షకుల విజేత',
        'is_hidden'                => 'దాగి ఉంది',
        'is_subscribed'            => 'చందా చేయబడింది',
        'is_visible'               => 'కనిపిస్తుంది',
        'is_winner'                => 'విజేత',
        'items'                    => 'అంశాలు',
        'key'                      => 'కీ',
        'last_name'                => 'చివరి పేరు',
        'lesson'                   => 'పాఠం',
        'line_address_1'           => 'లైన్ చిరునామా 1',
        'line_address_2'           => 'లైన్ చిరునామా 2',
        'login'                    => 'ప్రవేశించండి',
        'message'                  => 'సందేశం',
        'middle_name'              => 'మధ్య పేరు',
        'minute'                   => 'నిమిషం',
        'mobile'                   => 'మొబైల్',
        'month'                    => 'నెల',
        'name'                     => 'పేరు',
        'national_code'            => 'జాతీయ కోడ్',
        'number'                   => 'సంఖ్య',
        'password'                 => 'పాస్వర్డ్',
        'password_confirmation'    => 'పాస్వర్డ్ నిర్ధారణ',
        'phone'                    => 'ఫోన్',
        'photo'                    => 'ఫోటో',
        'portfolio'                => 'పోర్ట్‌ఫోలియో',
        'postal_code'              => 'పోస్టల్ కోడ్',
        'preview'                  => 'ప్రివ్యూ',
        'price'                    => 'ధర',
        'product_id'               => 'ఉత్పత్తి ID',
        'product_uid'              => 'ఉత్పత్తి UID',
        'product_uuid'             => 'ఉత్పత్తి UUID',
        'promo_code'               => 'ప్రోమో కోడ్',
        'province'                 => 'ప్రావిన్స్',
        'quantity'                 => 'పరిమాణం',
        'reason'                   => 'కారణం',
        'recaptcha_response_field' => 'recaptcha ప్రతిస్పందన ఫీల్డ్',
        'referee'                  => 'రిఫరీ',
        'referees'                 => 'రిఫరీలు',
        'reject_reason'            => 'కారణాన్ని తిరస్కరించండి',
        'remember'                 => 'గుర్తుంచుకోవాలి',
        'restored_at'              => 'వద్ద పునరుద్ధరించబడింది',
        'result_text_under_image'  => 'చిత్రం క్రింద ఫలిత వచనం',
        'role'                     => 'పాత్ర',
        'rule'                     => 'పాలన',
        'rules'                    => 'నియమాలు',
        'second'                   => 'రెండవ',
        'sex'                      => 'సెక్స్',
        'shipment'                 => 'రవాణా',
        'short_text'               => 'చిన్న వచనం',
        'size'                     => 'పరిమాణం',
        'skills'                   => 'నైపుణ్యాలు',
        'slug'                     => 'స్లగ్',
        'specialization'           => 'స్పెషలైజేషన్',
        'started_at'               => 'వద్ద ప్రారంభించారు',
        'state'                    => 'రాష్ట్రం',
        'status'                   => 'హోదా',
        'street'                   => 'వీధి',
        'student'                  => 'విద్యార్థి',
        'subject'                  => 'విషయం',
        'tag'                      => 'ట్యాగ్',
        'tags'                     => 'టాగ్లు',
        'teacher'                  => 'గురువు',
        'terms'                    => 'నిబంధనలు',
        'test_description'         => 'పరీక్ష వివరణ',
        'test_locale'              => 'పరీక్ష లొకేల్',
        'test_name'                => 'పరీక్ష పేరు',
        'text'                     => 'వచనం',
        'time'                     => 'సమయం',
        'title'                    => 'శీర్షిక',
        'type'                     => 'రకం',
        'updated_at'               => 'వద్ద నవీకరించబడింది',
        'user'                     => 'వినియోగదారు',
        'username'                 => 'వినియోగదారు పేరు',
        'value'                    => 'విలువ',
        'year'                     => 'సంవత్సరం',
    ],
];