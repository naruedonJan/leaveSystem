<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use App\User;
use App\Event;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
        // $this->app->register(\Sven\ArtisanView\ServiceProvider::class);
    }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            // $event->menu->add([
            //     'text' => Request()->route()->getPrefix(),
            //     'url' => 'pages',
            //     'active' => ['pages', 'content', 'content/*', 'regex:@^content/[0-9]+$@']
            // ]);

            if ((Auth::user()->permission == 'boss' && Request()->route()->getPrefix() == '/admin') || (Auth::user()->permission == 'programer' && Request()->route()->getPrefix() == '/admin') || (Auth::user()->permission == 'admin' && Request()->route()->getPrefix() == '/admin') ||  Request()->route()->getPrefix() == '/admin') {
                $event->menu->add('ระบบลาออนไลน์');
                $event->menu->add(
                    [
                        'text'        => 'ปฏิทิน',
                        'url'         => 'admin/calendar',
                        'icon'        => 'far fa-calendar',
                        'icon_color' => 'green',
                    ],
                    [
                        'text'        => 'อนุมัติการลา',
                        'url'         => 'admin/approve',
                        'icon'        => 'fas fa-user-check',
                        'icon_color' => 'green',
                        'can'  => 'group-admin',
                        'label'       => Event::where('status', 0)->count(),
                        'label_color' => 'info',
                    ],
                    [
                        'text'        => 'ประวัติการลา',
                        'url'         => 'admin/history-leave',
                        'icon'        => 'fas fa-user',
                        'icon_color' => 'green',
                        'can'  => 'group-admin',
                    ]
                );

                $event->menu->add('ระบบจัดการพนักงาน');
                $event->menu->add(
                    [
                        'text'        => 'อนุมัติพนักงานใหม่',
                        'url'         => 'admin/new-emp',
                        'icon'        => 'fas fa-user-check',
                        'icon_color' => 'green',
                        'label'       => User::where('users.status', 0)->where('users.permission', '<>', 'boss')->count(),
                        'label_color' => 'danger',
                        'can'  => 'group-admin',
                    ],
                    [
                        'text'        => 'จัดการแผนก',
                        'url'         => 'admin/department',
                        'icon'        => 'fas fa-users',
                        'icon_color' => 'yellow',
                        'can'  => 'group-admin',
                    ],
                    [
                        'text' => 'พนักงาน',
                        'url'  => 'admin/employee',
                        'icon' => 'fas fa-fw fa-user',
                        'icon_color' => 'aqua',
                        'can'  => 'group-admin',
                    ],
                    [
                        'text'        => 'จัดการแอดมิน',
                        'url'         => 'admin/adminmanage',
                        'icon'        => 'fas fa-wrench',
                        'icon_color' => 'yellow',
                        'can'  => 'group-admin',
                    ]
                );
                $event->menu->add('การตั้งค่าระบบ');
                $event->menu->add(
                    [
                        'text' => 'Line Notify',
                        'url'  => 'admin/line',
                        'icon' => 'fab fa-line',
                        'icon_color' => 'green',
                        'can'  => 'group-admin',
                    ]
                    ,[
                        'text' => 'จัดการฟังก์ชันการแจ้งเตือน',
                        'url'  => 'admin/function-line',
                        'icon' => 'fab fa-line',
                        'icon_color' => 'green',
                        'can'  => 'group-admin',
                    ],[
                        'text' => 'จัดการไลน์บอท',
                        'url'  => 'admin/linebotsetting',
                        'icon' => 'fab fa-line',
                        'icon_color' => 'green',
                        'can'  => 'group-admin',
                    ]
                    // ,[
                    //     'text' => 'จัดการเงื่อนไขวันหยุด',
                    //     'url'  => 'admin/line',
                    //     'icon' => 'fas fa-fw fa-user',
                    //     'icon_color' => 'aqua',
                    //     'can'  => 'group-admin',
                    // ]
                );
                $event->menu->add('รายงาน');
                $event->menu->add(
                    [
                        'text'        => 'รายงาน',
                        'url'         => 'admin/report',
                        'icon'        => 'fas fa-chart-line',
                        'icon_color' => 'aqua',
                        'can'  => 'group-admin',
                    ]
                );
            } elseif (Request()->route()->getPrefix() == '/job') {
                $event->menu->add('ระบบลงงาน');
                $event->menu->add(
                    [
                        'text'        => 'หน้าแรก',
                        'url'         => 'job',
                        'icon'        => 'fas fa-chart-line',
                        // 'icon_color' => 'green',
                    ],
                    [
                        'text'    => 'Content',
                        'icon'    => 'fas fa-scroll',
                        // 'label'       => Event::where('status', 0)->count(),
                        'label_color' => 'info',
                        'submenu' => [
                            [
                                'text'        => 'ดูงานที่ลงแล้ว',
                                'url'         => 'job/content',
                                'icon'        => 'far fa-calendar',
                                'icon_color' => 'green',
                            ],
                            [
                                'text'        => 'ลงงาน',
                                'url'         => 'job/content/add',
                                'icon'        => 'far fa-file',
                                'icon_color' => 'yellow',
                                'can'  => 'group-emp',
                            ],
                            [
                                'text'        => 'เพิ่มเว็บ',
                                'url'         => 'job/content/addweb',
                                'icon'        => 'far fa-file',
                                'icon_color' => 'red',
                                'can'  => 'group-emp',
                            ],
                            // [
                            //     'text'        => 'โทเคน',
                            //     'url'         => 'job/token?dept_id=1',
                            //     'icon'        => 'far fa-token',
                            //     'icon_color' => 'red',
                            //     'can'  => 'group-admin',
                            // ],
                        ],
                    ],
                    [
                        'text'    => 'Social Media',
                        'icon'    => 'fab fa-facebook-square',
                        'label_color' => 'info',
                        'submenu' => [
                            [
                                'text'        => 'ดูงานที่ลงแล้ว',
                                'url'         => 'job/social',
                                'icon'        => 'far fa-calendar',
                                'icon_color' => 'green',
                            ],
                            [
                                'text'        => 'ลงงาน',
                                'url'         => 'job/social/add',
                                'icon'        => 'far fa-file',
                                'icon_color' => 'yellow',
                                'can'  => 'group-emp',
                            ],
                            [
                                'text'        => 'เพิ่มชื่อเพจ/ช่อง',
                                'url'         => 'job/social/addweb',
                                'icon'        => 'far fa-file',
                                'icon_color' => 'red',
                                'can'  => 'group-emp',
                            ],
                            // [
                            //     'text'        => 'โทเคน',
                            //     'url'         => 'job/token?dept_id=2',
                            //     'icon'        => 'far fa-token',
                            //     'icon_color' => 'red',
                            //     'can'  => 'group-admin',
                            // ],
                        ],
                    ],
                    [
                        'text'    => 'ตารางทำรายการ',
                        'icon'    => 'fas fa-table',
                        'label_color' => 'info',
                        'submenu' => [
                            [
                                'text'        => 'ดูงานที่ลงแล้ว',
                                'url'         => 'job/program',
                                'icon'        => 'far fa-calendar',
                                'icon_color' => 'green',
                            ],
                            [
                                'text'        => 'ลงงาน',
                                'url'         => 'job/program/add',
                                'icon'        => 'far fa-file',
                                'icon_color' => 'yellow',
                                'can'  => 'group-emp',
                            ],
                        ],
                    ],
                    [
                        'text'    => 'Graphic Design',
                        'icon'    => 'fas fa-images',
                        'label_color' => 'info',
                        'submenu' => [
                            [
                                'text'        => 'ดูงานที่ลงแล้ว',
                                'url'         => 'job/graphic',
                                'icon'        => 'far fa-calendar',
                                'icon_color' => 'green',
                            ],
                            [
                                'text'        => 'ลงงาน',
                                'url'         => 'job/graphic/add',
                                'icon'        => 'far fa-file',
                                'icon_color' => 'yellow',
                                'can'  => 'group-emp',
                            ],
                            // [
                            //     'text'        => 'โทเคน',
                            //     'url'         => 'job/token?dept_id=4',
                            //     'icon'        => 'far fa-token',
                            //     'icon_color' => 'red',
                            //     'can'  => 'group-admin',
                            // ],
                        ],
                    ],
                    [
                        'text'    => 'Backlink',
                        'icon'    => 'fas fa-link',
                        'label_color' => 'info',
                        'submenu' => [
                            [
                                'text'        => 'ดูงานที่ลงแล้ว',
                                'url'         => 'job/backlink',
                                'icon'        => 'far fa-calendar',
                                'icon_color' => 'green',
                            ],
                            [
                                'text'        => 'ลงงาน',
                                'url'         => 'job/backlink/add',
                                'icon'        => 'far fa-file',
                                'icon_color' => 'yellow',
                                'can'  => 'group-emp',
                            ],
                            [
                                'text'        => 'เพิ่มเว็บ',
                                'url'         => 'job/backlink/addweb',
                                'icon'        => 'far fa-file',
                                'icon_color' => 'red',
                                'can'  => 'group-emp',
                            ],
                            // [
                            //     'text'        => 'โทเคน',
                            //     'url'         => 'job/token?dept_id=5',
                            //     'icon'        => 'far fa-token',
                            //     'icon_color' => 'red',
                            //     'can'  => 'group-admin',
                            // ],
                        ],
                    ],
                    [
                        'text'    => 'Programmer',
                        'icon'    => 'fas fa-laptop-code',
                        'label_color' => 'info',
                        'submenu' => [
                            [
                                'text'        => 'ดูงานที่ลงแล้ว',
                                'url'         => 'job/programmer',
                                'icon'        => 'far fa-calendar',
                                'icon_color' => 'green',
                            ],
                            [
                                'text'        => 'ลงงาน',
                                'url'         => 'job/programmer/add',
                                'icon'        => 'far fa-file',
                                'icon_color' => 'yellow',
                                'can'  => 'group-emp',
                            ],
                            // [
                            //     'text'        => 'โทเคน',
                            //     'url'         => 'job/token?dept_id=6',
                            //     'icon'        => 'far fa-token',
                            //     'icon_color' => 'red',
                            //     'can'  => 'group-admin',
                            // ],
                        ],
                    ],
                    [
                        'text'    => 'โปรโมท',
                        'icon'    => 'fab fa-facebook-square',
                        'label_color' => 'info',
                        'submenu' => [
                            [
                                'text'        => 'ดูรายการโปรโมท',
                                'url'         => 'job/pagecost',
                                'icon'        => 'far fa-calendar',
                                'icon_color' => 'green',
                            ],
                            [
                                'text'        => 'ลงโปรโมท',
                                'url'         => 'job/pagecost/add',
                                'icon'        => 'far fa-file',
                                'icon_color' => 'yellow',
                                'can'  => 'group-emp',
                            ],
                            // [
                            //     'text'        => 'โทเคน',
                            //     'url'         => 'job/token?dept_id=7',
                            //     'icon'        => 'far fa-token',
                            //     'icon_color' => 'red',
                            //     'can'  => 'group-admin',
                            // ],
                        ],
                    ],
                    [
                        'text'    => 'Line@',
                        'icon'    => 'fab fa-line',
                        'label_color' => 'info',
                        'submenu' => [
                            [
                                'text'        => 'รายการตรวจสอบ',
                                'url'         => 'job/line/check',
                                'icon'        => 'far fa-calendar',
                                'icon_color' => 'green',
                            ],
                            [
                                'text'        => 'แสดงรายงานการตรวจ',
                                'url'         => 'job/line/report',
                                'icon'        => 'far fa-calendar',
                                'icon_color' => 'green',
                            ],
                            [
                                'text'        => 'เพิ่ม Line@',
                                'url'         => 'job/line/new',
                                'icon'        => 'far fa-file',
                                'icon_color' => 'yellow',
                                // 'can'  => 'group-emp',
                            ],
                            // [
                            //     'text'        => 'โทเคน',
                            //     'url'         => 'job/token?dept_id=8-',
                            //     'icon'        => 'far fa-token',
                            //     'icon_color' => 'red',
                            //     'can'  => 'group-admin',
                            // ],
                        ],
                    ]
                );
            }  elseif (Auth::user()->permission == 'employee' && Request()->route()->getPrefix() == '/leave') {
                $event->menu->add('ระบบลาออนไลน์');
                $event->menu->add(
                    [
                        'text'    => 'ระบบลาออนไลน์',
                        'icon'    => 'far fa-calendar',
                        'label'       => Event::where('status', 0)->count(),
                        'label_color' => 'info',
                        'submenu' => [
                            [
                                'text'        => 'ปฏิทิน',
                                'url'         => 'leave/calendar',
                                'icon'        => 'far fa-calendar',
                                'icon_color' => 'green',
                            ],
                            [
                                'text'        => 'ประวัติการลา',
                                'url'         => 'leave/history',
                                'icon'        => 'far fa-file',
                                'icon_color' => 'yellow',
                                'can'  => 'group-emp',
                            ],
                        ],
                    ]
                );
            }
        });
    }
}
