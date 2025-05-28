/* Load header widgets here */
export const widgetComponents = {
    QuickNavigationHeaderWidget: () => import("@peak/Layouts/Admin/HeaderWidgets/QuickNavigationHeaderWidget.vue"),
    // LanguageSwitcherHeaderWidget: () => import("@peak/Layouts/Admin/HeaderWidgets/LanguageSwitcherHeaderWidget.vue"),
    ToggleFullScreenHeaderWidget: () => import("@peak/Layouts/Admin/HeaderWidgets/ToggleFullScreenHeaderWidget.vue"),
    VisitWebsiteHeaderWidget: () => import("@peak/Layouts/Admin/HeaderWidgets/VisitSiteHeaderWidget.vue"),
    NotificationsHeaderWidget: () => import("@peak/Layouts/Admin/HeaderWidgets/NotificationsHeaderWidget.vue")
};
