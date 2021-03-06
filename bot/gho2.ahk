#Persistent
CoordMode, ToolTip, screen
;SetTimer, WatchCursor, 100 
SetTimer BuffTask, off
BuffCheck := 1
Counter := 0
IsFullScreen := 0
SkillEnable1 := 0 ; 1
SkillEnable2 := 0 ; 1
SkillEnable3 := 0 ; 1
SkillEnable4 := 0 ; 1
PickUpEnable := 0 ; 1 
IsFullScreenX := 0
IsFullScreenY := 0
SearchCheck := 0
PixelCheck := 0
Return

=::
    ExitApp
Return

^!a::
    ExitApp
Return
 
^a::
    gosub InitialPos
Return

^f::
    If FullScreenActive:=!FullScreenActive
	{
        IsFullScreenX := 5
        IsFullScreenY := 30
	}
    Else
	{
        IsFullScreenX := 0
        IsFullScreenY := 0
	}    
Return

+^1::
    SkillEnable1 :=! SkillEnable1
Return

+^2::
    SkillEnable2 :=! SkillEnable2
Return

+^3::
    SkillEnable3 :=! SkillEnable3
Return

+^4::
    SkillEnable4 :=! SkillEnable4
Return

^d::
    PickUpEnable :=! PickUpEnable
Return

^b::
    gosub BuffTask
Return

BuffTask:
    BuffCheck := 1
Return

~Control::
    KeyPressControl := "D"
Return

^Space::
    SpacebarChecker := 1
    SendInput, {control up}
Return

~Space::  
    If ( Counter = 0 )
    {
        Counter := 1
        gosub InitialPos
    } 
    If Active:=!Active
	{
        Sleep, 100
		SetTimer MainTask, 100
        SetTimer NoMainTask, Off
	}
    Else
	{
        SetTimer MainTask, Off
        SetTimer NoMainTask, 1000
        SpacebarChecker := 1
        SendInput, {control up}
	}
Return

CheckPosition:
    gosub CheckAnyKeyPress
    If ( AnyKeyPress = 1 )
    {
        Return
    }
    
    If ( SearchCheck = 0 )
    {
        PixelSearch, CharacterX, CharacterY, StartCharacterX - 100, StartCharacterY - 100, StartCharacterX + 100, StartCharacterY + 100, 0x00E6FF, 3, Fast
    }
    Else
    {
        gosub SearchPixel
    }  
    
    If ErrorLevel
    {
        ;tooltip, Error 
    }
    Else
    {
        ErrorX := CharacterX - StartCharacterX
        ErrorY := CharacterY - StartCharacterY
        If ( Abs( ErrorX ) > 10 )
        {
            If ( ErrorX < 0 )
            {
                TransitionHold( "Right" )
            }
            Else 
            {
                TransitionHold( "Left" )
            }
            ArrivedX := 0
        }
        Else
        {
            ArrivedX := 1
        }
        If ( Abs( ErrorY ) > 5 )
        {
            If ( ErrorY < 0 )
            {
                SendInput, {control down}
                TransitionHold( "Down" )      
                SendInput, {control up}
            }
            Else 
            {
                TransitionHold( "Up" )            
            }
            ArrivedY := 0
        }
        Else
        {
            ArrivedY := 1
        }
        
        gosub LoopMovement
        ;gosub SquareMovement
        
        ;tooltip, CharacterX: %CharacterX%`nCharacterY: %CharacterY%
        ;tooltip
    }
Return

TransitionHold( HoldButton )
{
    Sleep, 100
    SendInput, {%HoldButton% down} ; don't use {%HoldButton% down } 
    Sleep, 350
    SendInput, {%HoldButton% up}
    Sleep, 10 
    Return
}

^+e::
    PixelSearch, MouseLoopX, MouseLoopY, 525, 62, 1020, 445, 0x00E6FF, 1, Fast
    ArrayCount++
    ArrayX[ArrayCount] :=  MouseLoopX
    ArrayY[ArrayCount] :=  MouseLoopY
    Sleep, 1000
Return

^+w::
    ArrayX := []
    ArrayY := []
    ArrayCount := 0
Return    


^w::
    SearchCheck :=! SearchCheck 
Return

^e::
    PixelCheck :=! PixelCheck
Return

^q::
    TransitionState := 1
    PixelCheck := 1
    
    PixelSearch, StartCharacterX, StartCharacterY, 525, 62, 1020, 445, 0x00E6FF, 1, Fast
    ;MouseGetPos, StartCharacterX, StartCharacterY
    StartCharacterX := StartCharacterX - IsFullScreenX 
    StartCharacterY := StartCharacterY - IsFullScreenY
    ;PixelGetColor, colorCharacter, %StartCharacterX%, %StartCharacterY%     
    ;tooltip, x: %StartCharacterX%`ny: %StartCharacterY%`ncolor: %colorCharacter% 
    
    StartCharacterSaveX := StartCharacterX
    StartCharacterSaveY := StartCharacterY
Return

MainTask:

    BuffCheck++
    PersonCheck++

    If ( BuffCheck >= 50 )
    {
        ;SkillExecute( MouseXV , MouseYV , colorV_start  ,"v" ,1 )
        SkillExecute( MouseXB , MouseYB , colorB_start  ,"b" ,1 )
        SkillExecute( MouseXN , MouseYN , colorN_start  ,"n" ,1 )
        BuffCheck := 0
    }
    If ( PixelCheck = 1 )
    {
        gosub CheckPosition
        If ( PersonCheck >= 50 )
        {
            PixelSearch, PersonCheckX, PersonCheckY, 525, 62, 1020, 445, 0x0000F7, 1, Fast
            If Not ErrorLevel
            {
                SoundBeep, 750, 1000
            }
            PersonCheck := 0
        }    
    }
    
    
    SkillExecute( MouseXHP, MouseYHP, colorHP_start ,6 ,1 ,"HP" )
    SkillExecute( MouseXMP, MouseYMP, colorMP_start ,5 ,1 ,"MP" )
    SkillExecute( MouseX1 , MouseY1 , color1_start  ,1 ,SkillEnable1 )
    SkillExecute( MouseX2 , MouseY2 , color2_start  ,2 ,SkillEnable2 )
    SkillExecute( MouseX3 , MouseY3 , color3_start  ,3 ,SkillEnable3 )
    SkillExecute( MouseX4 , MouseY4 , color4_start  ,4 ,SkillEnable4 )
    SendInput, {control up}
    ;KeyPressControl := "U"
    ;gosub ControlHold
    
Return

NoMainTask:
    SkillExecute( MouseXHP, MouseYHP, colorHP_start ,6 ,1 ,"HP" )
    SkillExecute( MouseXMP, MouseYMP, colorMP_start ,5 ,1 ,"MP" )
Return

CheckAnyKeyPress:
    GetKeyState, KeyPressSpace,   Space
    GetKeyState, KeyPressControl, Ctrl
    GetKeyState, KeyPressUp,      Up
    GetKeyState, KeyPressDown,    Down
    GetKeyState, KeyPressRight,   Right
    GetKeyState, KeyPressLeft,    Left
    If (   KeyPressSpace   = "D" 
        Or KeyPressControl = "D" 
        Or KeyPressUp      = "D" 
        Or KeyPressDown    = "D" 
        Or KeyPressRight   = "D" 
        Or KeyPressLeft    = "D" )
    {
        AnyKeyPress := 1
        CountDownTime := 0
        If ( ( KeyPressRight = "D" Or KeyPressLeft = "D" )
            And ( KeyPressUp = "U" ) 
            And ( KeyPressControl = "U" ) 
            And ( PickUpEnable = 1 ) )
        {
            gosub ControlHold
        }
        
    }
    Else
    {
        If ( CountDownTime >= 5 )
        {
            AnyKeyPress := 0
        }
        Else
        {
            AnyKeyPress := 1
            CountDownTime++
        }
    }
Return

ControlHold:
    SendInput, {control down}
    Sleep, 10
    SendInput, {control up}
    Sleep, 10
Return

SkillExecute( MouseXNOW , MouseYNOW ,PixelStart ,SkillButton ,SkillEnable ,Refills = "OFF" )
{   
    IfWinNotActive, SoulSaverOnline
    {
        Return  
    }   
    
    gosub CheckAnyKeyPress
    global AnyKeyPress
    global SpacebarChecker
    If ( ( AnyKeyPress = 0 And SkillEnable = 1 ) Or ( Refills = "HP" ) Or ( Refills = "MP" ) )
    {
        PixelGetColor, PixelNow, %MouseXNOW%, %MouseYNOW%
        If ( Refills = "OFF" )
        {
            If ( PixelNow = PixelStart )
            {    
                LoopChecker := 0
                Loop
                {
                    SendInput, {%SkillButton% down}
                    Sleep, 100
                    SendInput, {%SkillButton% up}
                    Sleep, 10
                    PixelGetColor, PixelNow, %MouseXNOW%, %MouseYNOW%
                    If ( PixelNow != PixelStart 
                        Or LoopChecker >= 20 
                        Or SpacebarChecker = 1 )
                    {
                        SpacebarChecker := 0
                        Break
                    }
                    LoopChecker++
                }        
            }
        }
        Else
        {           
            If ( PixelNow != PixelStart )
            {      
                SendInput, {%SkillButton% down}
                Sleep, 100
                SendInput, {%SkillButton% up}
                Sleep, 10     
            } 
        }
    }
    ;ToolTip, Screen :`t`t1 %PixelNow% 2 %PixelStart% 
    Return 
}

InitialPos:

    CoordMode, mouse, Client
    ;MouseGetPos, MouseX, MouseY
    MouseX := 180 - IsFullScreenX 
    MouseY := 43 - IsFullScreenY
    MouseXHP := MouseX
    MouseXMP := MouseX    
    MouseX1 := MouseXHP + 250 
    MouseX2 := MouseX1 + 35
    MouseX3 := MouseX2 + 35
    MouseX4 := MouseX3 + 35
    MouseX5 := MouseX4 + 35
    MouseX6 := MouseX5 + 35
    
    MouseXV := MouseX4
    MouseXB := MouseXV + 35
    MouseXN := MouseXB + 35

    MouseYHP := MouseY
    MouseYMP := MouseYHP + 14      
    MouseY1 := MouseYHP + 682
    MouseY2 := MouseY1
    MouseY3 := MouseY1
    MouseY4 := MouseY1
    MouseY5 := MouseY1 + 15
    MouseY6 := MouseY1 + 15   
    
    MouseYV := MouseY4 + 35
    MouseYB := MouseYV
    MouseYN := MouseYB
    
    MouseXHP := MouseXHP - 20
    MouseXMP := MouseXMP - 50

    PixelGetColor, colorHP_start, %MouseXHP%, %MouseYHP%
    PixelGetColor, colorMP_start, %MouseXMP%, %MouseYMP%    
    PixelGetColor, color1_start, %MouseX1%, %MouseY1%
    PixelGetColor, color2_start, %MouseX2%, %MouseY2%
    PixelGetColor, color3_start, %MouseX3%, %MouseY3%
    PixelGetColor, color4_start, %MouseX4%, %MouseY4% 
    PixelGetColor, color5_start, %MouseX5%, %MouseY5%
    PixelGetColor, color6_start, %MouseX6%, %MouseY6%     
    PixelGetColor, colorV_start, %MouseXV%, %MouseYV%
    PixelGetColor, colorB_start, %MouseXB%, %MouseYB%
    PixelGetColor, colorN_start, %MouseXN%, %MouseYN%  
    
    If ( ( IsFullScreenX != 0 ) And ( IsFullScreenY != 0 ) )
    {
        colorHP_start := 0x4A4AFF
        colorMP_start := 0xFFEE4A
    }
    Else
    {
        colorHP_start := 0x0000FF
        colorMP_start := 0xFF7B00
    }
Return

PixelUpdate:

    PixelGetColor, colorHP, %MouseXHP%, %MouseYHP%
    PixelGetColor, colorMP, %MouseXMP%, %MouseYMP%    
    PixelGetColor, color1, %MouseX1%, %MouseY1%
    PixelGetColor, color2, %MouseX2%, %MouseY2%
    PixelGetColor, color3, %MouseX3%, %MouseY3%
    PixelGetColor, color4, %MouseX4%, %MouseY4%
    PixelGetColor, color5, %MouseX5%, %MouseY5%
    PixelGetColor, color6, %MouseX6%, %MouseY6% 
    PixelGetColor, colorV, %MouseXV%, %MouseYV%
    PixelGetColor, colorB, %MouseXB%, %MouseYB%
    PixelGetColor, colorN, %MouseXN%, %MouseYN% 

Return

WatchCursor:
    CoordMode, mouse, Screen ; Coordinates are relative to the desktop (entire screen).
    MouseGetPos, x_1, y_1, id_1, control_1
    
    CoordMode, mouse, Window ; Synonymous with Relative and recommended for clarity.
    MouseGetPos, x_2, y_2, id_2, control_2
    
    CoordMode, mouse, Client ; Coordinates are relative to the active window's client area
    MouseGetPos, x_3, y_3, id_3, control_3
    
    ToolTip, Screen :`t`tx %x_1% y %y_1% %PixelNow% %MouseXHP% %colorMP% %color1% %color2% %color3% %color4%`nWindow :`tx %x_2% y %y_2%`nClient :`t`tx %x_3% y %y_3%, % A_ScreenWidth-200, % A_ScreenHeight-200
return

^r::
    SendInput, {Enter down}
    Sleep, 500
    SendInput, {Enter up}
    Sleep, 500 
    Send, Happy 8th Anniversary
    Sleep, 500  
    SendInput, {Enter down}
    Sleep, 500
    SendInput, {Enter up}
    Sleep, 500
    
    SendInput, {Enter down}
    Sleep, 500
    SendInput, {Enter up}
    Sleep, 500 
    Send, Love 8th Anniversary
    Sleep, 500  
    SendInput, {Enter down}
    Sleep, 500
    SendInput, {Enter up}    
    Sleep, 500
    
    SendInput, {Enter down}
    Sleep, 500
    SendInput, {Enter up}
    Sleep, 500 
    Send, SoulSaver Forever
    Sleep, 500  
    SendInput, {Enter down}
    Sleep, 500
    SendInput, {Enter up}   
    Sleep, 500
    
Return

SearchPixel:
        PixelSearch, CharacterX, CharacterY, 62, 256, 920, 650, 0xF7F7FF, 1, Fast  
        If ErrorLevel
        {
            StartCharacterX := StartCharacterSaveX
            StartCharacterY := StartCharacterSaveY
            PixelSearch, CharacterX, CharacterY, StartCharacterX - 100, StartCharacterY - 100, StartCharacterX + 100, StartCharacterY + 100, 0x00E6FF, 1, Fast
            If ErrorLevel
            {
                Return
            }
        }   
        PixelSearch, StartCharacterX, StartCharacterY, StartCharacterX - 20 , StartCharacterY - 20, StartCharacterX + 20 , StartCharacterY +20 , 0xA4318C, 1, Fast
        If ErrorLevel
        {
            PixelSearch, StartCharacterX, StartCharacterY, 62, 256, 920, 650, 0xA4318C, 1, Fast
            If ErrorLevel
            {
                StartCharacterX := StartCharacterSaveX
                StartCharacterY := StartCharacterSaveY
                PixelSearch, CharacterX, CharacterY, StartCharacterX - 100, StartCharacterY - 100, StartCharacterX + 100, StartCharacterY + 100, 0x00E6FF, 1, Fast
                If ErrorLevel
                {
                    Return
                }
            }  
        }  
Return

^u::
    ArrayX := []
    ArrayY := []
    ArrayCount := 0
    Loop
    {
        KeyWait, Lbutton, Down
        MouseGetPos, MouseLoopX, MouseLoopY
        ArrayCount++
        Tooltip, Click %ArrayCount%
        ArrayX[ArrayCount] :=  MouseLoopX
        ArrayY[ArrayCount] :=  MouseLoopY
        If ( StopLoop = 1 Or ArrayCount >= 10 )
        {
            Tooltip
            ArrayCount := ArrayCount - 1
            StopLoop := 0
            Break
        }
        Sleep, 500
        Tooltip
    }  
Return

^u Up::
    StopLoop := 1
Return

LoopMovement:
    If ( ( ArrivedX = 1 ) And ( ArrivedY = 1 ) )
    {
        Loop % ArrayCount
        {
            If ( TransitionState = A_Index )
            {
                If ( A_Index + 1 > ArrayCount )
                {
                    StartCharacterX := ArrayX[1]
                    StartCharacterY := ArrayY[1]
                    TransitionState := 1
                }
                Else
                {
                    StartCharacterX := ArrayX[A_Index + 1]
                    StartCharacterY := ArrayY[A_Index + 1]
                    TransitionState++
                }
                Break
            }
            ;MsgBox % "Element number " . A_Index . " is " . ArrayX[A_Index] " , " ArrayY[A_Index]
        }
    }
Return

SquareMovement:        
        If ( ( ArrivedX = 1 ) And ( ArrivedY = 1 ) )
        {   
            If ( TransitionState = 1 )
            {
                StartCharacterX := StartCharacterX + 50
                StartCharacterY := StartCharacterY
                TransitionState := 2
            }
            Else If ( TransitionState = 2 )
            {
                StartCharacterX := StartCharacterX 
                StartCharacterY := StartCharacterY + 50
                TransitionState := 3
            }
            Else If ( TransitionState = 3 )
            {
                StartCharacterX := StartCharacterX - 50
                StartCharacterY := StartCharacterY
                TransitionState := 4
            }            
            Else If ( TransitionState = 4 )
            {
                StartCharacterX := StartCharacterX 
                StartCharacterY := StartCharacterY - 50
                TransitionState := 1
            }                
        }
Return       
