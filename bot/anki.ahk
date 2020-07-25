#Persistent
CoordMode, ToolTip, screen

Return

=::
    ExitApp
Return

F1::MouseGetPos, , , win_id, control, 
F2::Msgbox, control %control%`r`nwin_id %win_id%

1::ControlSend , %Control%, {1}, ahk_id %win_id%
2::ControlSend , %Control%, {2}, ahk_id %win_id%
3::ControlSend , %Control%, {3}, ahk_id %win_id%
4::ControlSend , %Control%, {4}, ahk_id %win_id%

Space::
ControlClick, x1 y1, ahk_id %win_id% 
ControlSend , %Control%, {Space}, ahk_id %win_id%
Return