export function getDuration(
    startDate,
    startTime,
    endDate,
    endTime,
    status,
) {
    
    if (status == "4") {
        const now = new Date();
        endDate = now.toISOString().split("T")[0];
        endTime = now.toTimeString().split(" ")[0].substring(0, 5);
    }

    const startDateTime = new Date(`${startDate}T${startTime}`);
    const endDateTime = new Date(`${endDate}T${endTime}`);

    const diffInMs = endDateTime - startDateTime;
    const diffInMinutes = Math.floor(diffInMs / (1000 * 60));
    const diffInHours = Math.floor(diffInMs / (1000 * 60 * 60));
    const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));

    if (diffInDays > 0) {
        const remainingHours = diffInHours % 24; // Get the remaining hours after full days
        if (remainingHours > 0) {
            return `${diffInDays} Dia${diffInDays > 1 ? "s" : ""}, ${remainingHours} Hr${remainingHours > 1 ? "s" : ""}`;
        } else {
            return `${diffInDays} Dia${diffInDays > 1 ? "s" : ""}`;
        }
    } else if (diffInHours > 0) {
        return `${diffInHours} Hr${diffInHours > 1 ? "s" : ""}`;
    } else {
        return `${diffInMinutes} Min${diffInMinutes > 1 ? "s" : ""}`;
    }
}