

import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime"
dayjs.extend(relativeTime);

const diffForHumans = (date) => {
    if (!date) return "Never";
    return dayjs(date).fromNow()
}

export default diffForHumans ;