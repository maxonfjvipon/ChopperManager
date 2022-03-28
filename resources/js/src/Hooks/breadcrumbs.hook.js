import {UnorderedListOutlined, UserOutlined} from "@ant-design/icons";

export const useBreadcrumbs = () => {
    const breadcrumb = (path, name, icon) => {
        return {
            path,
            icon,
            breadcrumbName: name,
        }
    }

    const projectsIndex = breadcrumb(route('projects.index'), 'Проекты', <UnorderedListOutlined/>)

    return {
        breadcrumb,

        projects: [projectsIndex],
        selections: [projectsIndex, breadcrumb(route('selections.dashboard', 0), 'Подборы')],
        pumps: [breadcrumb(route('pumps.index'), 'Насосы')],
        profile: [breadcrumb(route('users.profile'), 'Профиль', <UserOutlined/>)]
    }
}
