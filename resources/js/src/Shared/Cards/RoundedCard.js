import {Card} from "antd";

export const RoundedCard = ({children, ...rest}) => {
    return (
        <Card className="rounded-card"  {...rest}>
            {children}
        </Card>
    )
}
