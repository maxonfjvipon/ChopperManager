export const useDate = () => {
    const dateStringFromDB = dbDate => {
        const splitted = dbDate.split(dbDate.includes('T') ? 'T' : ' ')[0].split("-")
        return splitted[2] + "/" + splitted[1] + '/' + splitted[0]
    }

    return {dateStringFromDB}
}
