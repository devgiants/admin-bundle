<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 18/05/15
 * Time: 16:03
 */

namespace devgiants\AdminBundle\Event;


final class AdminEvents {
    const GENERATE_SUPER_ADMIN_MENU = "superadmin.generate_menu";
    const GENERATE_ADMIN_MENU = "admin.generate_menu";
    const GENERATE_PRODUCT_MANAGER_MENU = "product_manager.generate_menu";

    const RENDER_LIST_PREFIX = "admin.render.list.";
    const RENDER_LIST_BEFORE_PREFIX = "admin.render.list.before.";
    const RENDER_PRE_HEADER_PREFIX = "admin.render.pre.header.";
    const RENDER_HEADER_PREFIX = "admin.render.list.header.";
    const RENDER_ROW_PREFIX = "admin.render.list.row.";
    const RENDER_CELL_PREFIX = "admin.render.list.cell.";
    const RENDER_ACTIONS_PREFIX = "admin.render.list.row.actions.";
    const RENDER_LIST_AFTER_PREFIX = "admin.render.list.after.";
}