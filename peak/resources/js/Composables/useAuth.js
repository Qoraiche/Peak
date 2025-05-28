import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
export function useAuth() {
    const page = usePage();

    const user = computed(() => page.props.auth?.user || {});
    const roles = computed(() => user.value.role_names || []);
    const permissions = computed(() => user.value.permission_names || []);

    // Get admin roles from Inertia props
    const adminRoles = computed(() => page.props.config?.admin_roles || []);

    // Check if the user has at least one of the given roles
    const hasRole = (...roleNames) => {
        return roles.value.some(role => roleNames.includes(role));
    };

    const hasPermission = (...permissionNames) => {
        return permissions.value.some(permission => permissionNames.includes(permission));
    };

    // isAdmin as a computed property
    const isAdmin = computed(() => hasRole(...adminRoles.value));

    return {user, roles, hasRole, permissions, hasPermission, isAdmin};
}
