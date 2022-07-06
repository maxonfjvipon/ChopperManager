import {useEffect} from "react";
import {message} from "antd";

export const useContractors = (debouncedSearchValue, setContractors) => {

    useEffect(() => {
        if (!!debouncedSearchValue) {
            const url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/party";

            const token = "fef9ff490530693826b443662382a513395d5522";
            const options = {
                method: "POST",
                mode: "cors",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Authorization": "Token " + token
                },
                body: JSON.stringify({query: debouncedSearchValue, count: 20})
            }
            fetch(url, options)
                .then(response => response.json())
                .then(result => {
                    console.log(result)
                    setContractors(result.suggestions.map(suggestion => {
                        return {
                            name: suggestion.value + " / " + suggestion.data.inn + ' / ' + suggestion.data.address.value,
                            value: [suggestion.value, suggestion.data.inn, suggestion.data.address.data.region_kladr_id].join("?")
                        }
                    }))
                })
                .catch(error => message.error(error));
        }
    }, [debouncedSearchValue])
}
