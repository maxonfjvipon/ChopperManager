import {useEffect} from "react";
import {message} from "antd";
import {useDebounce} from "../../../../../../resources/js/src/Hooks/debounce.hook";

export const WithContractors = ({children, setContractors, contractorsSearchValue}) => {
    const debouncedContractorsSearchValue = useDebounce(contractorsSearchValue, 700)

    useEffect(() => {
        if (!!debouncedContractorsSearchValue) {
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
                body: JSON.stringify({query: debouncedContractorsSearchValue, count: 20})
            }
            fetch(url, options)
                .then(response => response.json())
                .then(result => {
                    // console.log(result)
                    setContractors(result.suggestions.map(suggestion => {
                        return {
                            name: suggestion.value + " / " + suggestion.data.inn + ' / ' + suggestion.data.address.data.region_with_type,
                            value: [suggestion.value, suggestion.data.inn, suggestion.data.address.data.region_kladr_id].join("?")
                        }
                    }))
                })
                .catch(error => message.error(error));
        }
    }, [debouncedContractorsSearchValue])

    return children;
}
