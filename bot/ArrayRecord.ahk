CoordMode, Mouse
ArrayX := []
ArrayY := []
Return

^r::
	SetTimer, ToolTip, 10
	KeyWait, Lbutton, Down
	MouseGetPos, MouseX, MouseY
    ArrayCount++
    ArrayX[ArrayCount] :=  MouseX
    ArrayY[ArrayCount] :=  MouseY
	SetTimer, ToolTip, OFF
    ToolTip
Return  

ToolTip:
	ToolTip Waiting for click...
Return

^s::
  Loop % ArrayCount
  {
      ElementX := ArrayX[A_Index]
      ElementY := ArrayY[A_Index]
      MsgBox % "Element number " . A_Index . " is " . ArrayX[A_Index] " , " ArrayY[A_Index]
  }
  ArrayCount := 0
Return  
  
  
  