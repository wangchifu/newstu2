<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SchoolsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\School::truncate(); //清空資料庫
        \App\Models\School::create([
            'name' => '縣立中山國小',
            'code' => '074601',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立民生國小',
            'code' => '074602',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立平和國小',
            'code' => '074603',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立南郭國小',
            'code' => '074604',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立南興國小',
            'code' => '074605',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立東芳國小',
            'code' => '074606',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立泰和國小',
            'code' => '074607',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立聯興國小',
            'code' => '074609',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立大竹國小',
            'code' => '074610',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立忠孝國小',
            'code' => '074614',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立芬園國小',
            'code' => '074615',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立花壇國小',
            'code' => '074621',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立三春國小',
            'code' => '074625',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立白沙國小',
            'code' => '074626',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立信義國小',
            'code' => '074774',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立大成國小',
            'code' => '074775',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '平和國小建和分校',
            'code' => '074603-1',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立文祥國小',
            'code' => '074622',
            'group_id'=>1,
        ]);
        \App\Models\School::create([
            'name' => '縣立新庄國小',
            'code' => '074631',
            'group_id'=>2,
        ]);
        \App\Models\School::create([
            'name' => '縣立線西國小',
            'code' => '074633',
            'group_id'=>2,
        ]);
        \App\Models\School::create([
            'name' => '縣立培英國小',
            'code' => '074632',
            'group_id'=>2,
        ]);
        \App\Models\School::create([
            'name' => '縣立大榮國小',
            'code' => '074630',
            'group_id'=>2,
        ]);
        \App\Models\School::create([
            'name' => '縣立伸仁國小',
            'code' => '074637',
            'group_id'=>2,
        ]);
        \App\Models\School::create([
            'name' => '縣立和仁國小',
            'code' => '074769',
            'group_id'=>2,
        ]);
        \App\Models\School::create([
            'name' => '縣立大嘉國小',
            'code' => '074629',
            'group_id'=>2,
        ]);
        \App\Models\School::create([
            'name' => '縣立和美國小',
            'code' => '074627',
            'group_id'=>2,
        ]);
        \App\Models\School::create([
            'name' => '縣立伸東國小',
            'code' => '074636',
            'group_id'=>2,
        ]);
        \App\Models\School::create([
            'name' => '縣立曉陽國小',
            'code' => '074634',
            'group_id'=>2,
        ]);
        \App\Models\School::create([
            'name' => '縣立大同國小',
            'code' => '074638',
            'group_id'=>2,
        ]);
        \App\Models\School::create([
            'name' => '縣立和東國小',
            'code' => '074628',
            'group_id'=>2,
        ]);
        \App\Models\School::create([
            'name' => '縣立新港國小',
            'code' => '074635',
            'group_id'=>2,
        ]);
        \App\Models\School::create([
            'name' => '縣立鹿港國小',
            'code' => '074639',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立文開國小',
            'code' => '074640',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立洛津國小',
            'code' => '074641',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立海埔國小',
            'code' => '074642',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立新興國小',
            'code' => '074643',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立草港國小',
            'code' => '074644',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立頂番國小',
            'code' => '074645',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立東興國小',
            'code' => '074646',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立鹿東國小',
            'code' => '074771',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立秀水國小',
            'code' => '074654',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立馬興國小',
            'code' => '074655',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立華龍國小',
            'code' => '074656',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立明正國小',
            'code' => '074657',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立陝西國小',
            'code' => '074658',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立育民國小',
            'code' => '074659',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立管嶼國小',
            'code' => '074647',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立文昌國小',
            'code' => '074648',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立西勢國小',
            'code' => '074649',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立大興國小',
            'code' => '074650',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立永豐國小',
            'code' => '074651',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立日新國小',
            'code' => '074652',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立育新國小',
            'code' => '074653',
            'group_id'=>3,
        ]);
        \App\Models\School::create([
            'name' => '縣立二林國小',
            'code' => '074736',
            'group_id'=>4,
        ]);
        \App\Models\School::create([
            'name' => '縣立中正國小',
            'code' => '074738',
            'group_id'=>4,
        ]);
        \App\Models\School::create([
            'name' => '縣立萬興國小',
            'code' => '074742',
            'group_id'=>4,
        ]);
        \App\Models\School::create([
            'name' => '縣立大城國小',
            'code' => '074747',
            'group_id'=>4,
        ]);
        \App\Models\School::create([
            'name' => '縣立竹塘國小',
            'code' => '074753',
            'group_id'=>4,
        ]);
        \App\Models\School::create([
            'name' => '縣立漢寶國小',
            'code' => '074764',
            'group_id'=>4,
        ]);
        \App\Models\School::create([
            'name' => '縣立王功國小',
            'code' => '074765',
            'group_id'=>4,
        ]);
        \App\Models\School::create([
            'name' => '縣立田中國小',
            'code' => '074698',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立三潭國小',
            'code' => '074699',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立大安國小',
            'code' => '074700',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立內安國小',
            'code' => '074701',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立東和國小',
            'code' => '074702',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立明禮國小',
            'code' => '074703',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立新民國小',
            'code' => '074776',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立社頭國小',
            'code' => '074704',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立橋頭國小',
            'code' => '074705',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立朝興國小',
            'code' => '074706',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立清水國小',
            'code' => '074707',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立湳雅國小',
            'code' => '074708',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立舊社國小',
            'code' => '074772',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立崙雅國小',
            'code' => '074773',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立二水國小',
            'code' => '074709',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立復興國小',
            'code' => '074710',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立源泉國小',
            'code' => '074711',
            'group_id'=>5,
        ]);
        \App\Models\School::create([
            'name' => '縣立北斗國小',
            'code' => '074712',
            'group_id'=>6,
        ]);
        \App\Models\School::create([
            'name' => '縣立萬來國小',
            'code' => '074713',
            'group_id'=>6,
        ]);
        \App\Models\School::create([
            'name' => '縣立螺青國小',
            'code' => '074714',
            'group_id'=>6,
        ]);
        \App\Models\School::create([
            'name' => '縣立螺陽國小',
            'code' => '074716',
            'group_id'=>6,
        ]);
        \App\Models\School::create([
            'name' => '縣立田尾國小',
            'code' => '074717',
            'group_id'=>6,
        ]);
        \App\Models\School::create([
            'name' => '縣立南鎮國小',
            'code' => '074718',
            'group_id'=>6,
        ]);
        \App\Models\School::create([
            'name' => '縣立溪州國小',
            'code' => '074727',
            'group_id'=>6,
        ]);
        \App\Models\School::create([
            'name' => '縣立合興國小',
            'code' => '074722',
            'group_id'=>6,
        ]);
        \App\Models\School::create([
            'name' => '縣立埤頭國小',
            'code' => '074721',
            'group_id'=>6,
        ]);
        \App\Models\School::create([
            'name' => '縣立中和國小',
            'code' => '074725',
            'group_id'=>6,
        ]);
        \App\Models\School::create([
            'name' => '縣立員林國小',
            'code' => '074681',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立靜修國小',
            'code' => '074682',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立僑信國小',
            'code' => '074683',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立員東國小',
            'code' => '074684',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立東山國小',
            'code' => '074686',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立青山國小',
            'code' => '074687',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立大村國小',
            'code' => '074689',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立村上國小',
            'code' => '074691',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立村東國小',
            'code' => '074692',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立永靖國小',
            'code' => '074693',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立永興國小',
            'code' => '074695',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立饒明國小',
            'code' => '074685',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立福興國小',
            'code' => '074696',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立德興國小',
            'code' => '074697',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立大西國小',
            'code' => '074690',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立明湖國小',
            'code' => '074688',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立福德國小',
            'code' => '074694',
            'group_id'=>7,
        ]);
        \App\Models\School::create([
            'name' => '縣立溪湖國小',
            'code' => '074660',
            'group_id'=>8,
        ]);
        \App\Models\School::create([
            'name' => '縣立湖西國小',
            'code' => '074662',
            'group_id'=>8,
        ]);
        \App\Models\School::create([
            'name' => '縣立湖東國小',
            'code' => '074663',
            'group_id'=>8,
        ]);
        \App\Models\School::create([
            'name' => '縣立湖南國小',
            'code' => '074664',
            'group_id'=>8,
        ]);
        \App\Models\School::create([
            'name' => '縣立湖北國小',
            'code' => '074777',
            'group_id'=>8,
        ]);
        \App\Models\School::create([
            'name' => '縣立埔鹽國小',
            'code' => '074666',
            'group_id'=>8,
        ]);
        \App\Models\School::create([
            'name' => '縣立好修國小',
            'code' => '074669',
            'group_id'=>8,
        ]);
        \App\Models\School::create([
            'name' => '縣立埔心國小',
            'code' => '074673',
            'group_id'=>8,
        ]);
        \App\Models\School::create([
            'name' => '縣立太平國小',
            'code' => '074674',
            'group_id'=>8,
        ]);
        \App\Models\School::create([
            'name' => '縣立舊館國小',
            'code' => '074675',
            'group_id'=>8,
        ]);
        \App\Models\School::create([
            'name' => '縣立東溪國小',
            'code' => '074661',
            'group_id'=>8,
        ]);
        \App\Models\School::create([
            'name' => '縣立媽厝國小',
            'code' => '074665',
            'group_id'=>8,
        ]);
        \App\Models\School::create([
            'name' => '縣立明聖國小',
            'code' => '074679',
            'group_id'=>8,
        ]);
        \App\Models\School::create([
            'name' => '彰化藝術高中國中部',
            'code' => '074308',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '陽明國中',
            'code' => '074505',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '彰安國中',
            'code' => '074506',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '彰德國中',
            'code' => '074507',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '彰興國中',
            'code' => '074538',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '彰泰國中',
            'code' => '074540',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '信義國中',
            'code' => '074541',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '芬園國中',
            'code' => '074509',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '花壇國中',
            'code' => '074526',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '溪湖國中',
            'code' => '074518',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '成功高中國中部',
            'code' => '074339',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '埔鹽國中',
            'code' => '074519',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '埔心國中',
            'code' => '074520',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '萬興國中',
            'code' => '074512',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '芳苑國中',
            'code' => '074517',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '大城國中',
            'code' => '074515',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '竹塘國中',
            'code' => '074514',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '二林高中國中部',
            'code' => '074313',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '草湖國中',
            'code' => '074516',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '原斗國中',
            'code' => '074537',
            'group_id'=>9,
        ]);
        \App\Models\School::create([
            'name' => '大村國中',
            'code' => '074525',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '員林國中',
            'code' => '074510',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '明倫國中',
            'code' => '074511',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '永靖國中',
            'code' => '074527',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '大同國中',
            'code' => '074536',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '和美高中國中部',
            'code' => '074323',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '和群國中',
            'code' => '074535',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '福興國中',
            'code' => '074521',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '伸港國中',
            'code' => '074524',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '線西國中',
            'code' => '074504',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '鹿鳴國中',
            'code' => '074503',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '鹿港國中',
            'code' => '074502',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '秀水國中',
            'code' => '074522',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '埤頭國中',
            'code' => '074534',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '北斗國中',
            'code' => '074501',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '溪州國中',
            'code' => '074532',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '社頭國中',
            'code' => '074530',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '二水國中',
            'code' => '074318',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '田尾國中',
            'code' => '074531',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '田中高中國中部',
            'code' => '074328',
            'group_id'=>10,
        ]);
        \App\Models\School::create([
            'name' => '溪陽國中',
            'code' => '074533',
            'group_id'=>10,
        ]);

    }
}
