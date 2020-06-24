CoordMode, Mouse, Screen
Counter = 0
Return

Esc::
    SendInput, {control up}
    ExitApp
Return

^!a::
    SendInput, {control up}
    ExitApp
Return


^q::
    global MainTaskTime = 100
    global TimeSecond := 1000
    If Active:=!Active
	{
        gosub CounterInit
        Sleep, 500
        SendInput, {control down}
		SetTimer MainTask, 100
	}
    Else
	{
        Sleep, 500
        SendInput, {control up}
        SetTimer MainTask, Off
	}
Return

MainTask:
    Click
    SkillExecute( Skill_1_cnt ,1 ,11 )
    SkillExecute( Skill_2_cnt ,2 ,11 )
    gosub CounterCount
Return

SkillExecute( ByRef SkillCounter, SkillButton ,SkillCoolDown ,NeedClick = "OFF" )
{
    If ( SkillCounter >= SkillCoolDown*(TimeSecond/MainTaskTime) )
    {
        Sleep, 500
        SendInput, {control up}
        Send, %SkillButton%
        If (NeedClick == "ON")
        {
            Sleep, 100
            Click
        }        
        SkillCounter := 0
        SendInput, {control down}
        Sleep, 500
    }
    Return SkillCounter
}

HoldClick( ByRef HoldPeriod = 1000 )
{
    Sleep, 500
    SendInput, {control up}
    SendInput, {lbutton down}
    Sleep, %HoldPeriod%
    SendInput, {lbutton up}
    SendInput, {control down}
    Sleep, 500
}

CounterInit:
    Skill_1_cnt := 99999
    Skill_2_cnt := 99999
    Skill_3_cnt := 99999
    Skill_4_cnt := 99999
    Skill_5_cnt := 99999
    Skill_6_cnt := 99999
Return

CounterCount:
    Skill_1_cnt++
    Skill_2_cnt++
    Skill_3_cnt++
    Skill_4_cnt++
    Skill_5_cnt++
    Skill_6_cnt++
Return

^m::
    SendInput, {Space down}
    Sleep, 100
    SendInput, {Space up}
    
    Sleep, 400
    
    SendInput, {c down}
    Sleep, 100
    SendInput, {c up}
    
    Sleep, 400
    
    SendInput, {z down}
    Sleep, 100
    SendInput, {z up}
    
Return

