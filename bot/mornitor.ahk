#Persistent
CoordMode, ToolTip, screen
SetTimer, WatchCursor, 100
return

WatchCursor:
MouseGetPos, MouseX, MouseY
MouseXHP := MouseX
MouseXMP := MouseX    
MouseX1 := MouseXHP + 250
MouseX2 := MouseX1 + 35
MouseX3 := MouseX2 + 35
MouseX4 := MouseX3 + 35
MouseYHP := MouseY
MouseYMP := MouseYHP + 14      
MouseY1 := MouseYHP + 682
MouseY2 := MouseY1
MouseY3 := MouseY1
MouseY4 := MouseY1
PixelGetColor, colorHP, %MouseXHP%, %MouseYHP%
PixelGetColor, colorMP, %MouseXMP%, %MouseYMP%    
PixelGetColor, color1, %MouseX1%, %MouseY1%
PixelGetColor, color2, %MouseX2%, %MouseY2%
PixelGetColor, color3, %MouseX3%, %MouseY3%
PixelGetColor, color4, %MouseX4%, %MouseY4%

CoordMode, mouse, Screen ; Coordinates are relative to the desktop (entire screen).
MouseGetPos, x_1, y_1, id_1, control_1

CoordMode, mouse, Window ; Synonymous with Relative and recommended for clarity.
MouseGetPos, x_2, y_2, id_2, control_2

CoordMode, mouse, Client ; Coordinates are relative to the active window's client area
MouseGetPos, x_3, y_3, id_3, control_3

ToolTip, Screen :`t`tx %x_1% y %y_1% %colorHP% %colorMP% %color1% %color2% %color3% %color4%`nWindow :`tx %x_2% y %y_2%`nClient :`t`tx %x_3% y %y_3%, % A_ScreenWidth-200, % A_ScreenHeight-200
return




Esc::ExitApp