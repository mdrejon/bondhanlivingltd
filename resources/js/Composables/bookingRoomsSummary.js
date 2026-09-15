// A booking can now hold several room lines (booking.rooms[]) instead of one room.
// These helpers summarize them for compact table cells: the first line's value,
// plus a "+N more" suffix when the booking has additional rooms.

export function roomTypeSummary(booking) {
    const rooms = booking.rooms ?? [];
    if (!rooms.length) return '—';
    const name = rooms[0].room_type?.name ?? 'Unassigned';
    return rooms.length > 1 ? `${name} +${rooms.length - 1} more` : name;
}

export function roomNumberSummary(booking) {
    const rooms = booking.rooms ?? [];
    if (!rooms.length) return '—';
    const number = rooms[0].room?.room_number;
    if (!number) return '—';
    return rooms.length > 1 ? `${number} +${rooms.length - 1}` : number;
}
