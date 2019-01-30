@extends('template.template')

@section('main-content')

    <section class="content-header">
        <h1>
            Subscription Plan
            <small>Subscription Plans</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ base_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><i class="fa fa-briefcase"></i> Subscription Plan</li>
        </ol>
        <br>
        <br>
    </section>

    <!-- Main content -->

    <div class="box box-success" id="active">

    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-briefcase"></i> Subscription Plans</h3>
        <div class="box-tools">
            <!--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>-->
        </div>
    </div>

    <section class="content">
        <div class="row" id="main-content">
            <div class="col-md-6">
                <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="WHY8BURG483TU">
                    <table>
                    <tr><td><input type="hidden" name="on0" value=""></td></tr><tr><td><select name="os0" onchange="changePlanLabel()" id="os0">
                        <option value="Split Fee Network">Split Fee Network : $75.00 USD - monthly</option>
                        <option value="Split Fee Network + Dashboard Advertising">Split Fee Network + Dashboard Advertising : $150.00 USD - monthly</option>
                    </select> </td></tr>
                    </table>
                    <br>
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="image" src="{{ base_url('public/img/paypal_sub_logo.png') }}" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                    <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            </div>

            <div class="col-md-6">
                <label id="subscription-title">Split Fee Network</label>
                <p id="subscription-desc">The Split Fee Network is the TalentCapture marketplace for recruiters to work together on jobs and candidates, and split client fees. In addition to being able to recruit for Employer TalentGrams, you will have full access to share your jobs with other recruiters in the TalentCapture network (who are all carefully vetted), and to view and respond to Agency TalentGrams from other agency recruiters. Agency recruiters agree to terms and keep 100% of the placement fee. There are no other fees that TalentCapture collects outside of the monthly subscription fee. The ideal vehicle to increase productivity, substantially add revenue, and quickly build your candidate pipeline or place those golden candidates you've been marketing.</p>
            </div>

            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>

    </div>

@endsection

@section('end-script')

<script type="application/javascript">
    function changePlanLabel()
    {
        var subscription_value = document.getElementById("os0").value;
        if (subscription_value == 'Split Fee Network') 
        {
            document.getElementById('subscription-title').innerHTML = 'Split Fee Network';
            document.getElementById('subscription-desc').innerHTML = 'The Split Fee Network is the TalentCapture marketplace for recruiters to work together on jobs and candidates, and split client fees. In addition to being able to recruit for Employer TalentGrams, you will have full access to share your jobs with other recruiters in the TalentCapture network (who are all carefully vetted), and to view and respond to Agency TalentGrams from other agency recruiters. Agency recruiters agree to terms and keep 100% of the placement fee. There are no other fees that TalentCapture collects outside of the monthly subscription fee. The ideal vehicle to increase productivity, substantially add revenue, and quickly build your candidate pipeline or place those golden candidates you\'ve been marketing.';
        }
        else
        {
            document.getElementById('subscription-title').innerHTML = 'Split Fee Network + Advertising on Agency Showcase';
            document.getElementById('subscription-desc').innerHTML = 'You have access to all TalentCapture features with this subscription. Showcase your profile directly on an employer\'s Dashboard and receive higher quality Employer TalentGrams. With this subscription option, your profile will appear on the Agency Showcase, which is a visible section only for employers. The hiring manager or corporate recruiter can easily view your profile and select to add you to their Preferred list of recruiters. Employers have the option to send jobs to only agency recruiters on their Preferred list. This subscription is a "no-brainer" as it provides you the ability to be noticed and gain new clients with minimal investment.';
        }
    }
     
</script>

@endsection
