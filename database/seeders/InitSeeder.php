<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $sql = <<<TEXT
INSERT INTO permissions (parent_id, name, path, title, component, hidden, no_cache, affix, no_tags_view, always_show, frame_src, active_menu, `rank`, icon, type, deleted_at, created_at, updated_at) VALUES (0, 'dashboard', '/', '首页', 'default-layout', 0, 0, 0, 0, 1, '', '', 0, 'IconApps', 0, null, null, '2023-04-13 12:37:17');
INSERT INTO permissions (parent_id, name, path, title, component, hidden, no_cache, affix, no_tags_view, always_show, frame_src, active_menu, `rank`, icon, type, deleted_at, created_at, updated_at) VALUES (1, 'workspace', '/dashboard/workspace', '工作台', 'dashboard/workplace/index', 0, 0, 0, 1, 1, '', '', 50, '', 0, null, null, '2023-04-14 20:48:26');
INSERT INTO permissions (parent_id, name, path, title, component, hidden, no_cache, affix, no_tags_view, always_show, frame_src, active_menu, `rank`, icon, type, deleted_at, created_at, updated_at) VALUES (0, 'system', '/system', '系统设置', 'default-layout', 0, 0, 1, 0, 1, '', '', 100, 'IconSettings', 0, null, null, '2023-04-13 12:10:53');
INSERT INTO permissions (parent_id, name, path, title, component, hidden, no_cache, affix, no_tags_view, always_show, frame_src, active_menu, `rank`, icon, type, deleted_at, created_at, updated_at) VALUES (3, 'permission', 'permission', '菜单设置', 'system/permission/index', 0, 0, 1, 0, 1, '', '', 50, '', 0, null, null, '2023-04-12 19:59:06');
INSERT INTO permissions (parent_id, name, path, title, component, hidden, no_cache, affix, no_tags_view, always_show, frame_src, active_menu, `rank`, icon, type, deleted_at, created_at, updated_at) VALUES (3, 'role', 'role', '角色管理', 'system/role/index', 0, 0, 1, 0, 1, '', '', 50, '', 0, null, '2023-04-12 19:54:12', '2023-04-12 19:59:12');
INSERT INTO permissions (parent_id, name, path, title, component, hidden, no_cache, affix, no_tags_view, always_show, frame_src, active_menu, `rank`, icon, type, deleted_at, created_at, updated_at) VALUES (3, 'user_list', 'user', '用户管理', 'system/user/index', 0, 0, 1, 0, 1, '', '', 50, '', 0, null, null, '2023-04-12 19:59:17');
INSERT INTO permissions (parent_id, name, path, title, component, hidden, no_cache, affix, no_tags_view, always_show, frame_src, active_menu, `rank`, icon, type, deleted_at, created_at, updated_at) VALUES (3, 'dict', 'dict', '字典管理', 'system/dict/index', 0, 0, 1, 0, 1, '', '', 50, '', 0, null, '2023-04-12 19:54:12', '2023-04-12 19:59:12');
INSERT INTO permissions (parent_id, name, path, title, component, hidden, no_cache, affix, no_tags_view, always_show, frame_src, active_menu, `rank`, icon, type, deleted_at, created_at, updated_at) VALUES (3, 'dict_value', 'dict-value', '键值管理', 'system/dict/value', 1, 0, 1, 1, 0, '', '', 50, '', 0, null, '2023-04-12 19:54:12', '2023-04-12 19:59:12');
INSERT INTO permissions (parent_id, name, path, title, component, hidden, no_cache, affix, no_tags_view, always_show, frame_src, active_menu, `rank`, icon, type, deleted_at, created_at, updated_at) VALUES (0, 'user_center', '/user', '用户中心', 'default-layout', 1, 0, 0, 0, 0, '', '', 200, '', 0, null, null, null);
INSERT INTO permissions (parent_id, name, path, title, component, hidden, no_cache, affix, no_tags_view, always_show, frame_src, active_menu, `rank`, icon, type, deleted_at, created_at, updated_at) VALUES (9, 'Info', 'info', '用户设置', 'user/setting/index', 1, 0, 0, 0, 0, '', '', 50, '', 0, null, null, null);

TEXT;
        $commands = explode(";", $sql);
        DB::beginTransaction();
        foreach($commands as $_sql) {
            $_sql = trim($_sql);
            if(!$_sql) {
                continue;
            }
            DB::insert($_sql);
        }
        DB::commit();
    }
}
