export const useRedirect = () => {
    return {
        redirectTo: path => {
            window.location.href = path
        }
    }
}
