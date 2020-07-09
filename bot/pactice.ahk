; n := 1 = 1
; w := "d"   d
; w := d     [ ]    (need to assign d)
; w = "d"    "d"
; w = d      d
; n := "1"   1
; n := 1     1
; n = 1      1
; a = b or a = "b" assign same expression and number check = true 
a:: 
    n := 1
    w := "d"  
    Msgbox, %n%
    Msgbox, %w%
    If n == 1
    {
        Msgbox, Q 
    }
    Else
    {
        Msgbox, q
    }
return

q::
    n = 1
    w = "d"  
    Msgbox, %n%
    Msgbox, %w%
return

; If run like if but it effect with :: only
#If w = "d" 
{
    Msgbox,"Q"
    b::r
}
#If ; If no shape  b::n will error
    Msgbox,"q"
    b::n 

;;;;;;;;;;;;;;;;;;;;;;
#If n = 1 
{
    e::r
}
#If
    e::n 
;;;;;;;;;;;;;;;;;;;;;;    
#If w == "d" 
{
    t::r
}
#If
    t::n
;;;;;;;;;;;;;;;;;;;;;;   
#If n == 1   
{
    f::r
}
#If
    f::n
;;;;;;;;;;;;;;;;;;;;;;
Esc::
    ExitApp
return


