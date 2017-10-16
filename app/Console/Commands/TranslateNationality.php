<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SoccerlifePlayerOnTR;
use App\Models\TransfermarktPlayer;


class TranslateNationality extends Command
{

    public $natiolalities =[
        "Афганистан"=> "Afghanistan",
        "Албания"=> "Albania",
        "Алжир"=> "Algeria",
        "Андорра" => "Andorra",
        "Ангола"=> "Angola",
        "Антигуа и Барбуда" => "AntiguaandBarbuda",
        "Аргентина"=> "Argentina",
        "Армения"=> "Armenia",
        "Аруба" => "Aruba",
        "Австралия"=> "Australia",
        "Австрия"=> "Austria",
        "Азербайджан"=> "Azerbaijan",
        "Бахрейн" => "Bahrain",
        "Барбадос" => "Barbados",
        "Беларусь"=> "Belarus",
        "Бельгия"=> "Belgium",
        "Бенин" => "Benin",
        "Бермуды" => "Bermuda",
        "Боливия"=> "Bolivia",
        "Босния и Герцеговина"=> "Bosnia",
        "Бразилия"=> "Brazil",
        "Болгария"=> "Bulgaria",
        "Буркина-Фасо"=> "BurkinaFaso",
        "Бурунди"=> "Burundi",
        "Камерун" => "Cameroon",
        "Канада" => "Canada",
        "Кабо-Верде" => "CapeVerde",
        "Центрально-Африканская Республика" => "CentralAfricanRepublic",
        "Чад"=> "Chad",
        "Чили"=> "Chile",
        "Китай" => "China",
        "Колумбия" => "Colombia",
        "Коморские острова" => "Comoros",
        "Конго"=> "Congo",
        "ДР Конго"=> "CongoDR",
        "Коста-Рика" => "CostaRica",
        //    36 => "Coted",
        "Хорватия" => "Croatia",
        "Куба" => "Cuba",
        "Кюрасао" => "Curacao",
        "Кипр" => "Cyprus",
        "Чехия" => "CzechRepublic",
        "Дания"=> "Denmark",
        "Доминиканская Республика"=> "DominicanRepublic",
        "Эквадор" => "Ecuador",
        "Египет"=> "Egypt",
        "Сальвадор" => "ElSalvador",
        "Англия"=> "England",
        "Экваториальная Гвинея" => "EquatorialGuinea",
        "Эритрея" => "Eritrea",
        "Эстония" => "Estonia",
        "Эфиопия" => "Ethiopia",
        "Фарерские острова" => "FaroeIsland",
        "Финляндия" => "Finland",
        "Франция" => "France",
        //      55 => "FrenchGuiana",
        "Габон"=> "Gabon",
        "Грузия"=> "Georgia",
        "Германия"=> "Germany",
        "Гана"=> "Ghana",
        "Гибралтар"=> "Gibraltar",
        "Греция"=> "Greece",
        "Гренада" => "Grenada",
        "Гваделупа" => "Guadeloupe",
        "Гватемала" => "Guatemala",
        //      65 => "Guernsey",
        "Гвинея" => "Guinea",
        "Гайана" => "Guyana",
        "Гаити" => "Haiti",
        "Гондурас"=> "Honduras",
        "Гонконг"=> "Hongkong",
        "Венгрия"=> "Hungary",
        "Исландия" => "Iceland",
        "Индия" => "India",
        "Индонезия" => "Indonesia",
        "Иран"=> "Iran",
        "Ирак" => "Iraq",
        "Ирландия" => "Ireland",
        //      78 => "IsleofMan",
        "Израиль" => "Israel",
        "Италия" => "Italy",
        "Ямайка" => "Jamaica",
        "Япония" => "Japan",
        //      83 => "Jersey",
        //      84 => "Jordan",
        "Казахстан" => "Kazakhstan",
        "Кения" => "Kenya",
        "Южная Корея" => "Korea",
        "Косово" => "Kosovo",
        "Кыргызстан" => "Kyrgyzstan",
        "Латвия" => "Latvia",
        "Ливан" => "Lebanon",
        "Либерия" => "Liberia",
        "Ливия" => "Libya",
        "Лихтенштейн" => "Liechtenstein",
        "Литва"=> "Lithuania",
        "Люксембург" => "Luxembourg",
        "Македония" => "Macedonia",
        "Мадагаскар" => "Madagascar",
        "Мали" => "Mali",
        "Мальта" => "Malta",
        "Мартиника" => "Martinique",
        "Мавритания" => "Mauritania",
        "Маврикий" => "Mauritius",
        "Мексика"=> "Mexico",
        "Молдавия" => "Moldova",
        "Черногория" => "Montenegro",
        "Монсеррат"=> "Montserrat",
        "Марокко" => "Morocco",
        "Мозамбик" => "Mozambique",
        "Намибия"=> "Namibia",
        "Нидерланды" => "Netherlands",
        "Новая Каледония" => "Neukaledonien",
        "Новая Зеландия" => "NewZealand",
        "Никарагуа" => "Nicaragua",
        "Нигер" => "Niger",
        "Нигерия" => "Nigeria",
        "Северная Ирландия" => "NorthernIreland",
        "Норвегия" => "Norway",
        "Пакистан"=> "Pakistan",
        "Непал" => "Nepal",
        "Панама" => "Panama",
        "Папуа - Новая Гвинея" => "PapuaNewGuinea",
        "Парагвай" => "Paraguay",
        "Перу" => "Peru",
        "Филиппины" => "Philippines",
        "Польша" => "Poland",
        "Португалия" => "Portugal",
        "Катар" => "Qatar",
        "Румыния" => "Romania",
        "Россия" => "Russia",
        "Руанда" => "Rwanda",
        //      132 => "R",
        "Сан-Марино" => "SanMarino",
        "Сан-Томе и Принсипи" => "SaoTomeandPrincipe",
        "Саудовская Аравия" => "SaudiArabia",
        "Шотландия" => "Scotland",
        "Сенегал" => "Senegal",
        "Сербия" => "Serbia",
        "Сьерра-Леоне" => "SierraLeone",
        "Словакия" => "Slovakia",
        "Словения" => "Slovenia",
        "Сомали" => "Somalia",
//        "Сингапур" => "",
        "ЮАР" => "SouthAfrica",
        "Судан" => "SouthernSudan",
        "Испания" => "Spain",
        "Шри-Ланка" => "SriLanka",
        //      147 => "St",
        "Свазиленд" => "Swaziland",
        "Швеция" => "Sweden",
        "Швейцария" => "Switzerland",
        "Сирия" => "Syria",
        "Таджикистан" => "Tajikistan",
        "Танзания" => "Tanzania",
        "Таиланд" => "Thailand",
        "Гамбия" => "TheGambia",
        "Того" => "Togo",
        "Тринидад и Тобаго" => "TrinidadandTobago",
        "Тунис" => "Tunisia",
        "Турция" => "Turkey",
        "Туркменистан" => "Turkmenistan",
        "Уганда" => "Uganda",
        "Украина" => "Ukraine",
        "США" => "UnitedStates",
        "Уругвай" => "Uruguay",
        "Узбекистан" => "Uzbekistan",
        "Венесуэла"=> "Venezuela",
        "Вьетнам"=> "Vietnam",
        "Уэльс" => "Wales",
        "Йемен" => "Yemen",
        "Замбия" => "Zambia",
        "Зимбабве"=> "Zimbabwe"
    ];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:nationalities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        SoccerlifePlayerOnTR::chunk(100, function($soccerlife_players) {
            foreach ($soccerlife_players as $soccerlife_player)
            {
              $soccerlife_player->nationality = $this->natiolalities[$soccerlife_player->nationality]??$soccerlife_player->nationality;
              $soccerlife_player->save();
            }
        });
        dd('finish');
    }
}
