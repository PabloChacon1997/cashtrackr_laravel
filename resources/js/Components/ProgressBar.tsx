import { buildStyles, CircularProgressbar } from "react-circular-progressbar";
import 'react-circular-progressbar/dist/styles.css'

type Props = {
  percentageUse: number
}
export default function ProgressBar({ percentageUse }: Props) {
  return (
    <CircularProgressbar
      value={percentageUse}
      styles={buildStyles({
        pathColor: '#3c0366',
        trailColor: '#f5f5f5',
        textSize: 8,
        textColor: '#3c0366'
      })}
      text={`${percentageUse}% Gastado`}
    />
  )
}
