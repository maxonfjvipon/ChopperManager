import {VictoryAxis, VictoryChart, VictoryLegend, VictoryLine, VictoryScatter, VictoryVoronoiContainer, VictoryTheme} from "victory";
import React, {useMemo, useState} from "react";
import {RoundedCard} from "../Shared/Cards/RoundedCard";
import Lang from "../../translation/lang";
// import Paper from "@material-ui/core/Paper";

export const useGraphic = () => {
    const [stationToShow, setStationToShow] = useState(null)
    const [workingPoint, setWorkingPoint] = useState(null)
    const [defaultSystemPerformance, setDefaultSystemPerformance] = useState([])

    const defaultDiagramId = "chart"

    const Diagram = ({multiline = false, toShow, width, height, id, workingPoint}) => {
        const diagramLine = (line, index) => (
            <VictoryLine
                key={"vline" + index}
                interpolation="linear" data={line}
                style={{data: {stroke: "black"}}}
            />
        )
        const scatterStyle = color => {
            return {
                data: {fill: color},
                labels: {fill: color}
            }
        }
        return useMemo(() => (
            <VictoryChart
                width={width}
                height={height - 20}
                domain={{y: [0, toShow?.yMax || 100]}} // todo
                containerComponent={<VictoryVoronoiContainer/>}
                id={'victory-chart'}
                theme={VictoryTheme.material}
            >
                <VictoryAxis
                    style={{
                        tickLabels: {padding: 1},
                        axisLabel: {padding: 20}
                    }}
                    orientation="bottom"
                    label={Lang.get('graphic.axis.flow')}
                />
                <VictoryAxis
                    style={{
                        tickLabels: {padding: 1},
                        axisLabel: {padding: 20}
                    }}
                    dependentAxis
                    label={Lang.get('graphic.axis.head')}
                />
                {multiline
                    ? (toShow
                        ? [...toShow?.lines].map((line, index) => (
                            diagramLine(line, index)
                        )) : <></>)
                    : (diagramLine(toShow?.line || [], 1))
                }
                <VictoryLine
                    interpolation="linear"
                    data={
                        toShow?.systemPerformance || defaultSystemPerformance || []
                    }
                    style={{data: {stroke: "red"}}}
                />
                <VictoryScatter
                    data={toShow != null
                        ? [{
                            x: +toShow.intersectionPoint.x,
                            y: +toShow.intersectionPoint.y
                        }]
                        : []}
                    size={6}
                    style={scatterStyle('green')}
                />

                <VictoryScatter
                    data={workingPoint ? [{
                        x: +workingPoint.x,
                        y: +workingPoint.y
                    }] : []}
                    size={6}
                    style={scatterStyle('red')}
                />
                <VictoryLegend
                    x={450} y={20}
                    centerTitle
                    orientation="vertical"
                    gutter={20}
                    colorScale={["red", "green"]}
                    data={(workingPoint && toShow) ? [
                        {
                            name: Lang.get('graphic.legend.working_point') +
                                "\n" + Lang.get('graphic.legend.flow') + ": " + workingPoint.x +
                                "\n" + Lang.get('graphic.legend.head') + ": " + workingPoint.y
                        },
                        {
                            name: Lang.get('graphic.legend.intersection_point') +
                                "\n" + Lang.get('graphic.legend.flow') + ": " + (toShow.intersectionPoint.x).toFixed(1) +
                                "\n" + Lang.get('graphic.legend.head') + ": " + (toShow.intersectionPoint.y).toFixed(1)
                        },
                    ] : []}
                />
            </VictoryChart>
        ), [toShow, workingPoint, defaultSystemPerformance])
    }

    const PSHCDiagram = ({multiline, width = 600, height = 450}) => {
        return <Diagram
                id={defaultDiagramId}
                toShow={stationToShow}
                multiline={multiline}
                height={height}
                width={width}
                workingPoint={workingPoint}
            />
    }

    return {
        PSHCDiagram,
        workingPoint,
        setWorkingPoint,
        stationToShow,
        setStationToShow,
        setDefaultSystemPerformance,
    }
}
