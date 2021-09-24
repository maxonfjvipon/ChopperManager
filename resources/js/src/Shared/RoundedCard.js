import {useStyles} from "../Hooks/styles.hook";
import {Card} from "antd";

export const RoundedCard = ({radius = 10, children, style, ...rest}) => {
    const {borderRadius} = useStyles()

    return (
        <Card style={{...borderRadius(radius), ...style}} {...rest}>
            {children}
        </Card>
    )
}
