import {SelectionsRoutes} from "../components/routes/selections.routes";
import {AuthRoutes} from "../components/routes/auth.routes";
import {ProjectsRoutes} from "../components/routes/projects.routes";

export const useRoutes = () => {
    return {
        authRoutes: AuthRoutes,
        selectionsRoutes: SelectionsRoutes,
        projectsRoutes: ProjectsRoutes
    }
}
