import moment from "moment";

export const useDate = () => {
    const dateStringFromDB = dbDate => {
        const splitted = dbDate.split(dbDate.includes('T') ? 'T' : ' ')[0].split("-")
        return splitted[2] + "/" + splitted[1] + '/' + splitted[0]
    }

    const compareDate = (date1, date2, format = 'DD.MM.YYYY hh:mm') => {
        return new Date(moment(date1, format).toDate()) - new Date(moment(date2, format).toDate())
    }

    return {dateStringFromDB, compareDate}
}
