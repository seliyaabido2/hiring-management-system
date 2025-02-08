@extends('layouts.admin')
@section('content')
    <div class="content">
        <div class="row justify-content-center total-number">

            <div class="row ">
              <!--Total number of register employers /.col-->
              @if (getUserRole(Auth()->user()->id) == 'Super Admin' || getUserRole(Auth()->user()->id) == 'Admin')
                <div class="col-sm-6 col-lg-3">
                    {{-- <a href="{{ route('admin.users.index') }}"> --}}
                  <div class="card text-white ">
                    <div class="card-body">

                      <div class="text-value">{{ isset($employerUserCount) ? $employerUserCount : 0 }}</div>
                      <div><b>Total number of registered employers</b></div>
                    </div>


                  </div>
                {{-- </a> --}}
                </div>
              @endif

              <!-- Total number of register recruiters  /.col-->

              @if (getUserRole(Auth()->user()->id) == 'Super Admin' || getUserRole(Auth()->user()->id) == 'Admin')
                <div class="col-sm-6 col-lg-3">
                    {{-- <a href="{{ route('admin.users.index') }}"> --}}
                  <div class="card text-white ">
                    <div class="card-body">

                      <div class="text-value">{{ isset($recruiterUserCount) ? $recruiterUserCount : 0 }}</div>
                      <div><b>Total number of registered recruiters</b></div>
                    </div>

                  </div>
                    {{-- </a> --}}
                </div>
              @endif


              <!-- Total number of published jobs /.col-->
              @if (getUserRole(Auth()->user()->id) == 'Super Admin' || getUserRole(Auth()->user()->id) == 'Admin' || getUserRole(Auth()->user()->id) == 'Employer'  )
                <div class="col-sm-6 col-lg-3">
                    {{-- <a href="{{ route('admin.employerJobs.requestJobs') }}"> --}}
                  <div class="card text-white ">
                    <div class="card-body">

                      <div class="text-value">{{ isset($jobPostRequestCnt) ? $jobPostRequestCnt : 0 }}</div>
                      <div><b>Total number of jobs</b></div>
                    </div>

                  </div>
                {{-- </a> --}}
                </div>
              @endif

              <!-- Total number of Active jobs /.col-->

                <div class="col-sm-6 col-lg-3">
                    {{-- <a href="{{ url('admin/employerJobs/status/activeJobs') }}"> --}}
                  <div class="card text-white ">
                    <div class="card-body">

                      <div class="text-value">{{ isset($activeJobPostCnt) ? $activeJobPostCnt : 0 }}</div>
                      <div><b>Total number of Active jobs</b></div>
                    </div>


                  </div>
                {{-- </a> --}}
                </div>


                @if (getUserRole(Auth()->user()->id) == 'Recruiter' )

                <div class="col-sm-6 col-lg-3">
                  {{-- <a href="{{ url('admin/employerJobs/status/activeJobs') }}"> --}}
                <div class="card text-white ">
                  <div class="card-body">

                    <div class="text-value">{{ isset($totelcandidateCnt) ? $totelcandidateCnt : 0 }}</div>
                    <div><b>Total number of candidates in DB</b></div>
                  </div>

                </div>
              {{-- </a> --}}
              </div>


              <div class="col-sm-6 col-lg-3">
                {{-- <a href="{{ url('admin/employerJobs/status/activeJobs') }}"> --}}
              <div class="card text-white ">
                <div class="card-body">

                  <div class="text-value">{{ isset($candidatehiredCnt) ? $candidatehiredCnt : 0 }}</div>
                  <div><b>Total number of Hired candidates</b></div>
                </div>

              </div>
            {{-- </a> --}}
            </div>


            <div class="col-sm-6 col-lg-3">
              {{-- <a href="{{ url('admin/employerJobs/status/activeJobs') }}"> --}}
            <div class="card text-white ">
              <div class="card-body">

                <div class="text-value">{{ isset($inProcessCandidateCnt) ? $inProcessCandidateCnt : 0 }}</div>
                <div><b>Total number of candidates in pipeline</b></div>
              </div>

            </div>
          {{-- </a> --}}
          </div>


                @endif
              @if (getUserRole(Auth()->user()->id) == 'Employer' )

              <div class="col-sm-6 col-lg-3">
                {{-- <a href="{{ url('admin/employerJobs/status/DeActiveJobs') }}"> --}}
                  <div class="card text-white ">
                    <div class="card-body">

                      <div class="text-value">{{ isset($inProcessCandidateCnt) ? $inProcessCandidateCnt : 0 }}</div>
                      <div><b>Total number of candidate In Process</b></div>
                    </div>

                  </div>
                {{-- </a> --}}
            </div>

            <div class="col-sm-6 col-lg-3">
              {{-- <a href="{{ url('admin/employerJobs/status/DeActiveJobs') }}"> --}}
                <div class="card text-white ">
                  <div class="card-body">

                    <div class="text-value">{{ isset($closedJobCnt) ? $closedJobCnt : 0 }}</div>
                    <div><b>Total number of closed<br> jobs</b></div>
                  </div>

                </div>
              {{-- </a> --}}
          </div>

          @endif

            </div>

            <div class="row">
                <!-- Total number of candidate selected /.col-->
                @if (getUserRole(Auth()->user()->id) == 'Super Admin' || getUserRole(Auth()->user()->id) == 'Admin' || getUserRole(Auth()->user()->id) == 'Employer' )
                    <div class="col-sm-6 col-lg-3">
                      {{-- <a href="{{ route('admin.candidate.candidateSelected') }}"> --}}
                        <div class="card text-white ">
                          <div class="card-body">

                            <div class="text-value">{{ isset($candidateCnt) ? $candidateCnt : 0 }}</div>
                            <div><b>Total number of candidate selected</b></div>
                          </div>

                        </div>
                      {{-- </a> --}}
                    </div>
                @endif

                 <!-- Total number of candidate selected /.col-->
                 @if (getUserRole(Auth()->user()->id) == 'Super Admin' || getUserRole(Auth()->user()->id) == 'Admin' || getUserRole(Auth()->user()->id) == 'Employer' )
                  <div class="col-sm-6 col-lg-3">
                    {{-- <a href="{{ route('admin.employerJobs.activeJobs') }}"> --}}
                      <div class="card text-white ">
                        <div class="card-body">

                          <div class="text-value">{{ isset($vacanciesCnt) ? $vacanciesCnt : 0 }}</div>
                          <div><b>Total number of vacancies published</b></div>
                        </div>

                      </div>
                    {{-- </a> --}}
                  </div>
                @endif




                <!--Total number of InActive jobs /.col-->
                @if (getUserRole(Auth()->user()->id) == 'Employer' )
                  <div class="col-sm-6 col-lg-3">
                      {{-- <a href="{{ url('admin/employerJobs/status/DeActiveJobs') }}"> --}}
                        <div class="card text-white ">
                          <div class="card-body">

                            <div class="text-value">{{ isset($saveCandidateCnt) ? $saveCandidateCnt : 0 }}</div>
                            <div><b>Total number of saved candidates</b></div>
                          </div>

                        </div>
                      {{-- </a> --}}
                  </div>

                @endif



                <!--Total number of InActive jobs /.col-->
                @if (getUserRole(Auth()->user()->id) == 'Super Admin' || getUserRole(Auth()->user()->id) == 'Admin' || getUserRole(Auth()->user()->id) == 'Employer' )
                  <div class="col-sm-6 col-lg-3">
                      {{-- <a href="{{ url('admin/employerJobs/status/DeActiveJobs') }}"> --}}
                        <div class="card text-white ">
                          <div class="card-body">

                            <div class="text-value">{{ isset($deactiveJobPostCnt) ? $deactiveJobPostCnt : 0 }}</div>
                            <div><b>Total number of InActive jobs</b></div>
                          </div>

                        </div>
                      {{-- </a> --}}
                  </div>
                @endif

                    <!--Total number of Candidate in BOD DB /.col-->
                    @if (getUserRole(Auth()->user()->id) == 'Super Admin'  ||  getUserRole(Auth()->user()->id) == 'Admin')
                    <div class="col-sm-6 col-lg-3">
                        {{-- <a href="{{ url('admin/employerJobs/status/DeActiveJobs') }}"> --}}
                          <div class="card text-white ">
                            <div class="card-body">

                              <div class="text-value">{{ isset($BodCandidateCount) ? $BodCandidateCount : 0 }}</div>
                              <div><b>Total number of Candidate in BOD DB</b></div>
                            </div>

                          </div>
                        {{-- </a> --}}
                    </div>
                  @endif



                <!-- /.col-->
              </div>

        </div>
    </div>
@endsection



@section('scripts')
    @parent
@endsection
