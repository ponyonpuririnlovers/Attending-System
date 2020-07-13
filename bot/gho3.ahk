#Persistent
CoordMode, ToolTip, screen

BuffCheck    := 0
Counter      := 0

SkillEnable1 := 0 ; 1
SkillEnable2 := 0 ; 1
SkillEnable3 := 0 ; 1
SkillEnable4 := 0 ; 1
PickUpEnable := 0 ; 1 

IsFullScreen  := 0
IsFullScreenX := 0
IsFullScreenY := 0

SearchTargetCheck   := 0
PixelCheck          := 0
TransitionState     := 1
ArrayX              := []
ArrayY              := []

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

^1::
    SkillEnable1 :=! SkillEnable1
Return

^2::
    SkillEnable2 :=! SkillEnable2
Return

^3::
    SkillEnable3 :=! SkillEnable3
Return

^4::
    SkillEnable4 :=! SkillEnable4
Return

^d::
    PickUpEnable :=! PickUpEnable
Return

^w::
    PixelCheck          := 1
    SearchTargetCheck   := 1
Return

^e::
    PixelCheck          := 0
    SearchTargetCheck   := 0
    ArrayCount          := 0
    TransitionState     := 1
Return

^q::

    PixelCheck          := 1
    SearchTargetCheck   := 0

    PixelSearch, MouseLoopX, MouseLoopY, 525, 62, 1020, 445, 0x00E6FF, 1, Fast
    ArrayCount++
    ArrayX[ArrayCount] :=  MouseLoopX
    ArrayY[ArrayCount] :=  MouseLoopY
    Sleep, 250

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

    If ( SearchTargetCheck = 0 )
    {
        AbsX := 10
        AbsY := 5

        PixelSearch, CharacterX, CharacterY, 525, 62, 1020, 445, 0x00E6FF, 1, Fast
        If ErrorLevel
        {
            Return 
        }
    }
    Else
    {
        AbsX := 200
        AbsY := 100

        PixelSearch, CharacterX, CharacterY, 62, 256, 920, 650, 0xF7F7FF, 1, Fast  
        If ErrorLevel
        {
            Return
        }   
        PixelSearch, StartCharacterX, StartCharacterY, StartCharacterX - 100 , StartCharacterY - 50, StartCharacterX + 100 , StartCharacterY + 50 , 0xA4318C, 1, Fast
        If ErrorLevel
        {
            PixelSearch, StartCharacterX, StartCharacterY, 62, 256, 920, 650, 0xA4318C, 1, Fast
            If ErrorLevel
            {
                Return
            }  
        }  
    } 

    ErrorX := CharacterX - StartCharacterX
    ErrorY := CharacterY - StartCharacterY
    If ( Abs( ErrorX ) > AbsX )
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
    If ( Abs( ErrorY ) > AbsY )
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

    If ( SearchTargetCheck = 0 )
    {
        gosub LoopMovement 
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

^m::
    ;PixelSearch, StartCharacterX, StartCharacterY, 525, 62, 1020, 445, 0x00E6FF, 1, Fast
    MouseGetPos, StartCharacterX, StartCharacterY
    StartCharacterX := StartCharacterX - IsFullScreenX 
    StartCharacterY := StartCharacterY - IsFullScreenY
    PixelGetColor, colorCharacter, %StartCharacterX%, %StartCharacterY%     
    tooltip, x: %StartCharacterX%`ny: %StartCharacterY%`ncolor: %colorCharacter% 
Return