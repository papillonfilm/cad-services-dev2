 <div bgcolor="#f5f5f5">
  <table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f5f5f5" style="table-layout:fixed; background-color: #f5f5f5; ">
    <tbody>
      <tr>
        <td><br><br>
			<table bgcolor="#f5f5f5" align="center">
            <tbody>
              <tr>
                <td width="640">
					<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#ffffff" style="background-color: #fff; ">
                    <tbody>
                      <tr>
                        <td>
						@if($returnHTML)
							<img src='{{ $emailLogoUrl }}' width='300px' height='80px' border='0' alt='Logo'> 
						@else
							<img src='{{$message->embed($emailLogo)}}' width='300px' height='80px' border='0' alt='Logo'> 
						@endif
						</td>
                      </tr>
                    </tbody>
                  </table>
                  <table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#dcdcdc" style="background-color: #dcdcdc; ">
                    <tbody>
						 
                      <tr>
                        <td align="left" style="padding:5px;font-size: 5px;">&nbsp;  </td>
                      </tr>
                     
                    </tbody>
                  </table>
                  <table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" style="background-color: #ffffff">
                    <tbody>
                      <tr>
                        <td align="left" style="padding:20px"><table cellspacing="0" cellpadding="0" border="0">
                            <tbody>
                              <tr>
                                <td valign="top" align="left"><p>
                                  <font color="#666666" face="'HelveticaNeue-Regular', 'Helvetica Neue Regular', 'Helvetica Neue', Helvetica, Arial, sans-serif" style="font-size:14px">
									 {!!$msg or ''!!}
									<br><Br>
									</font>
								</td>
                              </tr>
                              <tr>
                                <td valign="top" align="center"><p>
                                  <font color="#666666" face="'HelveticaNeue-Regular', 'Helvetica Neue Regular', 'Helvetica Neue', Helvetica, Arial, sans-serif" style="font-size:14px" > 
                                  
                               
                                  
                                  
                                  <br />
 
                                  
                                  
                                  </font>
                                  </p></td>
                              </tr>
                              <tr>
                              <td>
                             <font color="#666666" face="'HelveticaNeue-Regular', 'Helvetica Neue Regular', 'Helvetica Neue', Helvetica, Arial, sans-serif" style="font-size:14px"> 
                                   Cordially,<br><br>
                                  {{$signature or ''}} </font>
							 </td>
                              
                              </tr>
                            </tbody>
                          </table></td>
                      </tr>
                      
                      
                    </tbody>
                  </table>
                  <table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#666c74" align="center" style="background-color: #666c74">
                    <tbody>
                      <tr>
                        <td width="100%" valign="top" align="left" style="padding-left:30px;padding-right:30px;padding-top:10px;padding-bottom:10px">&nbsp;</td>
                      </tr>
                    </tbody>
                  </table>
                  <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                    <tbody>
                      <tr>
                        <td align="left" style="padding-top:7px"><font color="#999999" face="'HelveticaNeue-Regular', 'Helvetica Neue Regular', 'Helvetica Neue', Helvetica, Arial, sans-serif" style="font-size:10px;  ">Please do not reply to this email. Emails sent to this address will not be answered. <br>
                          <br>
                          Copyright &copy; {{$siteName}} All rights reserved.<br>
                         {!! $trackingImage !!} </font></td>
                      </tr>
                    </tbody>
                  </table><br><br></td>
              </tr>
            </tbody>
          </table></td>
      </tr>
    </tbody>
  </table>
</div>
