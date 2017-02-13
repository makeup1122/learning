Set objShell = CreateObject("WScript.Shell")
' Set mouse = New SetMouse
' mouse.getpos x,y
do 
WScript.Sleep 100
objShell.SendKeys "{ }"
' objShell.SendKeys "{DOWN}"
loop