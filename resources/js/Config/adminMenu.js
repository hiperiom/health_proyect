// Admin menu configuration factory
const AdminMenu = (h, UserOutlined, DashboardOutlined, SafetyOutlined, router, route, collapsed, role) => ({
    icon: () => h(UserOutlined),
    key: 'admin_group',
    label: h('div',{class:"text-center"}, !collapsed.value ? role.name : h(UserOutlined)) ,
    type: 'group',
    children: [
        {
            icon: () => h(DashboardOutlined),
            key: 'dashboard',
            label: h('div', 'Inicio'),
            onClick: () => router.get(route('dashboard')),
        },
        {
            icon: () => h(UserOutlined),
            key: 'users.index',
            label: h('div', 'Usuarios'),
            onClick: () => router.get(route('users.index')),
        },
        {
            icon: () => h(SafetyOutlined),
            key: 'roles.index',
            label: h('div', 'Roles'),
            onClick: () => router.get(route('roles.index')),
        },
        {
            icon: () => h(SafetyOutlined),
            key: 'medic-especialities.index',
            label: h('div', 'Especialidades'),
            onClick: () => router.get(route('medic-especialities.index')),
        },
        {
            icon: () => h(SafetyOutlined),
            key: 'medics.index',
            label: h('div', 'Médicos'),
            onClick: () => router.get(route('medics.index')),
        },
        {
            icon: () => h(SafetyOutlined),
            key: 'patients.index',
            label: h('div', 'Paciente'),
            onClick: () => router.get(route('patients.index')),
        },
        {
            icon: () => h(SafetyOutlined),
            key: 'patients.index',
            label: h('div', 'Pacientes'),
            onClick: () => router.get(route('patients.index')),
        },
    ]
});
export default AdminMenu;