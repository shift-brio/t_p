<style type="text/css">
	html,body{
		background: transparent !important;
	}
	*{
		transition: width .15s;
	}
	.sms_nav{
		position: fixed;
		bottom: 0;
		height: 50px;
		width: 100%;
		background: #f2f2f2;
		box-shadow: 0 0 4px 0px black;
		text-align: center;		
	}
	.fao_sms{
		width: 100%;
		height: 100%;
		overflow: hidden;
		background: #fff;
	}
	.sms-tab{
		height: 100%;
		width: auto;
		text-align: center;
		font-family: Article title, cursive;
		font-weight: 500;
		color: #000;
		background: transparent !important;
		border: none !important;		
		outline: none;
		display: inline-flex;		
		font-size: .9em;
		margin: 0px !important;
		transition: background .25s, color .15s;
	}
	.sms-tab.active{
		background: linear-gradient(to bottom,rgba(0,0,0,.5),rgba(0,0,0,.2)) !important;
		color:#fff;
		border-top: 3px solid darkblue !important;
	}
	.sms-tab > *{
		transition: transform .25s;
	}
	.sms-tab:active > *{
		transform: scale3d(.8,.8,.8);
	}
	.sms-tab:hover{
		background: linear-gradient(to bottom,rgba(0,0,0,.5),rgba(0,0,0,.2)) !important;
		color:rgba(0,0,0,.5);
		border-top: 3px solid rgba(0,0,0,.5) !important;		
	}
	.sms-tab.active > i{
		color: #fff;
	}	
	.sms-tab > i{		
		margin-right: 3px;
		color: rgba(0,0,0,.5);
	}	
	.sms-loader{
		height: 3px;
		width: 100%;
		position: fixed;
		bottom: 0;
		z-index: 100;
		margin-bottom: 50px;
		background: transparent;
	}
	.sms-loader-in{
		height: 100%;
		background: #fff;
		box-shadow: 0px 0px 10px #000;
		display: none;
		animation:progress_infin 1.5s linear infinite;
	}
	.sms-row{
		width: 100%;
		height: calc(100% - 50px);
	}
	.sms-cont{
		height: 100%;
		background: #fafafa;
		padding: 0px !important;
		margin: 0px !important;
		box-shadow: 0 0 3px grey;
	}
	.sms-head{
		height: 40px;
		background: lightgrey;
		width: 100%;
	}
	.sms-title{
		height: 100%;
		padding-left: 5px;
		font-weight: 500;
		font-family: article title,cursive;
		color: #fff;
		padding-top: 10px;
	}
	.sms-tools{
		margin-top: -40px;
		height: 100%;
		padding-top: 2px;
		padding-right: 5px;
	}
	.sms-tool{
		background: #f2f2f2 !important;
		height: 35px;
		width: 35px;
		padding: 5px;
		border-radius: 50%;
		border:2px solid rgba(0,0,0,.8);
		color: red;
		transition:transform .25s;
	}
	.sms-tool:active{
		transform: scale3d(.8,.8,.8);
	}
	.sms-body{
		height: calc(100% - 40px);
		overflow-y: auto;
		width: 100%;
		padding: 3px;
	}
	.sms-group{
		width: 100%;
		padding: 3px;
		height: auto;
	}
	.sms-label{
		font-weight: 500;
		font-size: 1em;
		color: lightgrey;
		font-family: article title, cursive;
	}
	.sms-in{
		cursor: pointer;
		height: 34px;
		border-radius: 5px;
		outline: none !important;
		background: transparent;
		padding-left: 2px;
		width: 100%;
		font-weight: 500 !important;
		border: 2px solid lightgrey !important;
		transition: background .25s, color .15s, border .15s;
	}
	.sms-in:focus{
		background: #fff;
	}
	.add-item{
		background: #fff !important;
		width: 30px;
		height: 30px;
		padding: 0px;
		border-radius: 50%;
		border: none;
		font-weight: 500;
		transition: background .25s, transform .15s;
		box-shadow: 0px 0px 3px grey;
	}
	.add-item:active{
		background: transparent !important;
		transform: scale3d(.8,.8,.8);
	}
	.added-items{
		padding: 3px;
		padding-bottom: 8px;
		max-height: 150px;
		overflow-y: auto;
		height: auto !important;
		min-height: 40px;		
	}
	.request-item{
		display: inline-flex;
		padding: 3px;
		border-radius: 12px;
		background: #fff;		
		box-shadow: 0px 0px 5px #000;
		margin-top: 5px;
		margin-right: 3px;
	}	
	.item-tool{
		background: transparent !important;
		color: #cb0000;
		border:none !important;
		font-size: 1.5em !important;
		transition: transform .25s;
	}
	.item-tool:active{
		transform: scale3d(.8,.8,.8);
	}
	.item-descript{
		font-size: .9em;
		font-weight: 600;
		color:grey;
		margin-left: 2px;
		margin-top: 2px;
	}
	.item-qty{
		margin-left: 3px;
		padding: 3px;		
	}
	.adder{
		display: inline;
	}
	.adding-items{
		position: fixed;
		width: 300px;
		margin-left: 5px;
		top:0;		
		background: linear-gradient(to bottom,#fff,#fafafa);
		box-shadow: 0px 0px 5px #000;
		height: 350px;
		border-bottom-left-radius: 5px;
		border-bottom-right-radius: 5px;
		animation:scaler .25s ease-out;
		display: none;
	}
	@keyframes scaler{
		0%{
			height: 0px;
			width: 0px;
		}
		100%{
			width: 300px;
			height: 350px;
		}
	}
	.adding-head{
		height: 40px;
		width: 100%;
		background: #f2f2f2;
		padding: 3px;
		border-bottom: 2px solid lightgrey;
	}
	.adding-title{
		color: grey;
		font-weight: 600;
		height: 100%;
		padding-top: 5px;
	}
	.adding-tool{
		background: transparent !important;
		border:none;
		float: right;
		margin-top: -30px;
		transition: transform .25s;
	}
	.adding-tool:active{
		transform: scale3d(.8,.8,.8);
	}
	.adding-body{
		height: calc(100% - 40px);
		width: 100%;
		overflow-y: auto;		
		padding: 5px;
	}
	.stationery-item{
		width: 100%;
		margin-bottom: 5px;
		border-radius: 5px;
		background: #f2f2f2;
		padding: 3px;	
		display: inline-block;	
		box-shadow: 0px 0px 3px grey;
	}
	.stationery-tools{
		display: inline-flex;
		text-align: right;
		width: 120px;
		float: right;
		margin-top: -30px;
	}
	.stationery-name{
		font-weight: 600;
		padding: 5px;
		margin-top: 8px;
	}
	.stationery-qty{
		height: 100% !important;		
		margin-right: 10px !important;
		margin-top: 5px !important;
	}
	.stationery-indic{
		height: 30px;
		width: 30px !important;
		cursor: pointer;
		border:2px solid grey;
		margin-left: 3px;
		border-radius: 5px;
		transition: transform .25s, background .25s;
	}
	.stationery-indic:active{
		transform: scale3d(.8,.8,.8);
		background: grey;
	}
	.stationery-indic.selected{
		background: grey;
	}
	.stationery-indic.selected > i{
		color: #fff;
	}
	.sms-reason{
		max-height: 100px;
		max-width: 100%;
		overflow-y: auto;
		color: #000;
	}
	.submit-sms{
		border:2px solid #000;
		border-radius: 5px;
		font-weight: 600;
		color: #fff;
		background: #000 !important;
		padding: 5px;
		box-shadow: 0px 0px 2px #000;
		transition: transform .25s, background .25s;
	}
	.submit-sms:active{
		transform: scale3d(.9,.9,.9);
	}
	.new-item{
		display: none;
	}
	.sms-item{
		padding: 3px;
	}
	.requested-item{
		box-shadow: 0px 0px 3px grey;
		width: 100%;
		padding: 4px;
		margin-bottom: 4px;
		background: #fff;
		border-radius: 5px;	
		display: inline-block;
		cursor: pointer;
		transition: background .25s;	
	}
	.requested-item:hover{
		background: rgba(0,0,0,.5);
		box-shadow: none;
	}
	.request-user{
		font-weight: 600;
	}
	.request-date{
		color: darkgrey;
		margin-top: 3px;
		font-size: .9em;
	}
	.request-reason{
		margin-top: 3px;
		border-top: 1px solid lightgrey;
	}
	.request-tools{		
		padding: 2px;
		border-radius: 5px;
		padding-top: 5px;		
		z-index: 1000;		
		animation:zoomer .25s ease-out;		
	}
	@media only screen and (min-width: 800px){
		.request-tools{
			display: none;
			border:2px solid grey;
		}
	}
	.requested-item:hover > .request-tools{
		display: initial;		
	}
	.requested-item:hover > .request-meta >  .request-date{
		color: #fff;
	}
	.requested-item:hover > .request-tools > .request-tool{		
		color: #fff;
	}
	.request-tool{
		background: transparent !important;
		border:none;
		color: #000;
		z-index: 1000;
		transition:transform .25s;
	}
	.request-tool:active{
		transform: scale3d(.8,.8,.8);
	}
	.request-status{
		margin-left: 20px;
		padding: 5px;		
		border-top-right-radius: 6px;
		border-top-left-radius: 6px;
		font-weight: 600;		
	}
	.request-status.pending{
		color: orange;
	}
	.request-status.cancelled{
		color: red;
	}
	.request-status.approved{
		color: lightgreen;
	}
	.sms-more{
		background: transparent !important;
		border:none;
		font-weight: 600;
		color: #fff;
		height: 100%;
		transition:transform .25s;
	}
	.request-list{
		position: fixed;
		top: 0;
		width: 300px;
		height: auto;
		padding: 3px;
		background: #f2f2f2;
		max-height: 300px;
		display: none;
		animation:zoomer .25s ease-out;
		overflow-y: auto;
		border-bottom-right-radius: 5px;
		border-bottom-left-radius: 5px;
		box-shadow: 0px 0px 5px #000;
	}
	.request-tbl{
		width: 100%;
		border:1px solid grey;
	}
	td,th{
		border-left: 1px solid;
	}
	tr,td,th{
		padding:3px !important;
		border-color: grey !important;
	}
	.close-list{
		background: transparent !important;
		border:none;
		transition:transform .25s;		
	}
	.sms-projects{
		position: fixed;
		box-shadow: 0px 0px 5px #000;
		background: #fff;
		width: 300px;
		height: 320px;	
		display: none;	
		animation:scaler .25s ease-out;
		border-bottom-right-radius: 5px;		
	}
	.projects-head{
		display: inline-block;
		background: #f2f2f2;
		width: 100%;
		height: 40px;
		box-shadow: 0px 0px 2px grey;
	}
	.project-tools{		
		margin-top: -34px;
	}
	.project-tool{
		height: 100%;
		background: transparent !important;
		border:none;		
		margin-right: 5px;
		transition: transform .25s;		
	}
	.projects-title{
		height: 100%;
		font-weight: 600;
		padding: 3px;
		padding-top: 5px !important;		
	}
	.projects-body{
		height: calc(100% - 40px);
		width: 100%;
		padding: 5px;
		background: linear-gradient(to bottom, #fff, lightgrey);
		overflow-y: auto;
	}
	.add-project{
		width: 280px;
		height: 105px;
		margin-top: 10px;
		box-shadow: 0px 0px 5px #000;
		position: fixed;
		border-radius: 5px;		
		display: none;
		animation:zoomer .25s ease-out;
	}
	.p-btn{
		background: grey !important;
		color: #fff;
		padding: 5px;
		border-radius: 5px;
		font-weight: 600;
		border:none;
		transition: transform .25s;
	}
	.project-name{
		width: 100%;
		background: #f2f2f2;
	}
	.project-item{
		box-shadow: 0px 0px 5px #000;
		padding: 5px;
		border-radius: 3px;
		height: 40px;
		margin-bottom: 5px;
	}
	.project-name-cont{		
		font-weight: 600;
		padding-top: 3px;		
	}
	.project-tool-main{
		height: 100%;
		transition: transform .25s;
		background: transparent !important;
		color: #cb0000;
		border:none;
		margin-top: -25px;
	}
	.red-text{
		color: red !important;
	}
	.sms-deleter{
		height: 100px;
		width: 300px;
		display: none;
		position: fixed;
		box-shadow: 0px 0px 5px #000;
		background: #f2f2f2;
		animation:zoomer .25s ease-out;
		border-bottom-right-radius: 5px;
	}
	.deleter-text{
		color: red;
		padding-top: 20px;
		font-weight: 600;
	}
	.deleter-tools{
		width: 100%;
		padding: 5px;
	}
	.delete-tool{
		background: grey !important;
		color: #fff;
		transition: transform .25s;
		border:2px solid grey;
		padding: 5px;
		border-radius: 5px;
		font-weight: 600;
	}
	.del-go{
		color: red;
		background: #000 !important;
		border-color:#000 !important;
	}
	.sms-requesters{
		height: 320px;
		width: 320px;		
		position: fixed;
		display: none;
		box-shadow: 0px 0px 5px #000;
		background: #f2f2f2;
		animation:slide_down .25s ease-out;
		border-bottom-right-radius: 5px;
	}
	.requesters-head{
		height: 40px;
		background: lightgrey;
		width: 100%;
	}
	.add-requester{
		width: 280px;
		height: 105px;
		margin-top: 10px;
		box-shadow: 0px 0px 5px #000;
		position: fixed;
		border-radius: 5px;
		display: none;
		margin-left: 5px;
		background: #fafafa;				
		animation:zoomer .25s ease-out;
	}
	.requester-email{
		width: 100%;
		background: transparent;
	}
	.requesters-body{
		width: 100%;
		height: calc(100% - 40px);
		overflow-y: auto;
		padding: 5px;
	}
	.requester{
		width: 100%;
		border-radius: 5px;
		padding: 3px;
		background: #fff;
		height: 40px;
		margin-bottom: 5px;
		box-shadow: 0px 0px 5px #000;
	}
	.requester-name{
		height: 100%;
		font-weight: 600;
		color: #000;
		padding-top: 5px;		
	}
	.requester-tools{
		margin-top: -31px;
	}
	.requester-tool{
		background: transparent !important;
		border:none;
		transition: transform .25s;
	}
	.rm-requester{
		color: red;
	}
	.sms-pass-change{
		height: 180px;
		width: 320px;		
		position: fixed;		
		box-shadow: 0px 0px 5px #000;
		background: #f2f2f2;
		display: none;
		animation:slide_down .25s ease-out;
		border-bottom-right-radius: 5px;
	}
</style>
<div class="fao_sms">
	<div class="sms-row row">
		<div class="col s12 m12 l3"></div>
		<div class="col s12 m12 l6 sms-cont">
			<div class="sms-deleter">
				<div class="center deleter-text">
					Are you sure you want to delete this item?
				</div>
				<div class="deleter-tools">
					<button class="left clickable delete-tool del-cancel">Cancel</button>
					<button data-tooltip="Delete"  data-position="left"  class="right tooltipped clickable  delete-tool del-go">Delete</button>
				</div>
			</div>
			<div class="sms-pass-change">
				<div class="sms-group">
					<lable class="sms-label">Current password</lable>
					<input placeholder="Current password" type="password" class="sms-in browser-default sms-curr">
				</div>
				<div class="sms-group">
					<lable class="sms-label">New password</lable>
					<input placeholder="New password" type="password" class="sms-in browser-default sms-new">
				</div>
				<div class="deleter-tools">
					<button class="left clickable material-icons delete-tool del-cancel">close</button>
					<button data-tooltip="Change"  data-position="left"  class="right tooltipped clickable  delete-tool change-go">Change</button>
				</div>
			</div>
			<div class="sms-requesters">
				<div class="requesters-head">
					<div class="projects-title">Requesters</div>
					<div class="project-tools">
						<button class="right clickable material-icons project-tool close-requesters">	close</button>
						<button data-tooltip="Add requester"  data-position="left"  class="right tooltipped clickable material-icons project-tool new-requester">add</button>
					</div>
				</div>
				<div class="add-requester">
					<div class="sms-group">
						<label class="sms-label">Email address</label>
						<input placeholder="Email" type="email" class="browser-default sms-in requester-email">
					</div>
					<div class="sms-group">
						<button class="left clickable p-btn requester-cancel">Cancel</button>
						<button class="right clickable p-btn save-requester">Add</button>
					</div>
				</div>
				<div class="requesters-body">
					<div class="requester">
						<div class="requester-name">
							briochieng97@gmail.com
						</div>
						<div class="requester-tools right">
							<button data-tooltip="Reset password"  data-position="left" class="requester-tool tooltipped material-icons clickable sett-requester">refresh</button>
							<button class="requester-tool clickable material-icons rm-requester">delete</button>
						</div>
					</div>
					<div class="requester">
						<div class="requester-name">
							briochieng97@gmail.com
						</div>
						<div class="requester-tools right">
							<button data-tooltip="Reset password"  data-position="left" class="requester-tool tooltipped material-icons clickable sett-requester">refresh</button>
							<button class="requester-tool clickable material-icons rm-requester">delete</button>
						</div>
					</div>
				</div>
			</div>
			<div class="sms-projects">
				<div class="projects-head">	
					<div class="projects-title">Projects</div>
					<div class="project-tools">
						<button class="right clickable material-icons project-tool close-projects">	close</button>
						<button data-tooltip="Add project"  data-position="left"  class="right tooltipped clickable material-icons project-tool close-projects">add</button>
					</div>
				</div>
				<div class="projects-body">
					<div class="add-project">
						<div class="sms-group">
							<label class="sms-label">Project name</label>
							<input placeholder="Project Name" type="text" class="browser-default sms-in project-name">
						</div>
						<div class="sms-group">
							<button class="left clickable p-btn sms-cancel">Cancel</button>
							<button class="right clickable p-btn project-add">Save</button>
						</div>
					</div>
					<div class="project-item">
						<div class="project-name-cont">Another project name</div>
						<button data-tooltip="Remove project" class="project-tool-main right material-icons tooltipped clickable">delete</button>
					</div>
					<div class="project-item">
						<div class="project-name-cont">Another project name</div>
						<button data-tooltip="Remove project" class="project-tool-main right material-icons tooltipped clickable">delete</button>
					</div>
					<div class="project-item">
						<div class="project-name-cont">Another project name</div>
						<button data-tooltip="Remove project" class="project-tool-main right material-icons tooltipped clickable">delete</button>
					</div>
				</div>
			</div>
			<div class="sms-head">
				<div class="sms-title">
					Requests made
				</div>
				<div class="right sms-tools">					
					<button data-tooltip="More tools" data-activates="more_dropdown"  data-position="left" class="material-icons sms-more  dropdown-trigger tooltipped clickable">more_vert</button>
					<ul class="dropdown-content" id="more_dropdown">
						<li class="log-out">
							<a><i class="material-icons">group</i>Requesters</a>
						</li>
						<li class="projects-option">
							<a><i class="material-icons">build</i>Projects</a>
						</li>
						<li class="projects-option">
							<a><i class="material-icons">settings</i>Password</a>
						</li>											
						<li class="log-out">
							<a><i class="material-icons">lock</i>Log Out</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="sms-body">
				<div class="request-list">
					<div class="center">
						<button class="material-icons clickable close-list">close</button>
					</div>
					<table class="request-tbl centered bordered">
						<thead>
							<tr>
								<th>Stationery</th>
								<th>Quantity</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Pencil</td>
								<td>30</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="sms-item sms-requests">
					<div class="requested-item">
						<div class="request-tools right">
							<button data-tooltip="View requested items" data-position="bottom" class="tooltipped request-tool material-icons">view_list</button>
							<button data-tooltip="Approve request" data-position="bottom" class="tooltipped request-tool material-icons">done</button>
							<button data-tooltip="Delete request" data-position="bottom" class="tooltipped request-tool red-text material-icons">delete</button>							
						</div>
						<div class="request-meta">
							<div class="request-user">Brian Ochieng ~ Project Name</div>
							<div class="request-date">01-01-2018 
								<span class="request-status pending">Pending</span>
							</div>
							<div class="request-reason">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div>
						</div>						
					</div>
					<div class="requested-item">
						<div class="request-tools right">
							<button data-tooltip="View requested items" data-position="bottom" class="tooltipped request-tool material-icons">view_list</button>							
						</div>
						<div class="request-meta">
							<div class="request-user">Kelvin Agengo</div>
							<div class="request-date">01-11-2018 
								<span class="request-status approved">Approved</span>
							</div>
							<div class="request-reason">Lorem ipsum dolor sit.</div>
						</div>						
					</div>
					<div class="requested-item">
						<div class="request-tools right">
							<button data-tooltip="View requested items" data-position="bottom" class="tooltipped request-tool material-icons">view_list</button>
							<button data-tooltip="Cancel request" data-position="bottom" class="tooltipped request-tool red-text material-icons">delete</button>							
						</div>
						<div class="request-meta">
							<div class="request-user">Kelvin Agengo</div>
							<div class="request-date">01-11-2018 
								<span class="request-status cancelled">Cancelled</span>
							</div>
							<div class="request-reason">This is the reason.</div>
						</div>						
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l3"></div>
	</div>
	<div class="sms-loader">
		<div class="sms-loader-in"></div>
	</div>
	<div class="sms_nav">
		<button data-tooltip="Pending requests" data-position="top" class="sms-tab tooltipped">
			<i class="material-icons">playlist_add_check</i>
			Pending
		</button>		
		<button data-tooltip="View requests" data-position="top" class="sms-tab active tooltipped">
			<i class="material-icons">view_list</i>
			All Requests
		</button>				
		<button data-tooltip="Request for stationery" data-position="top" class="sms-tab tooltipped">
			<i class="material-icons">add</i>
			New
		</button>		
	</div>
</div>