<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="row justify-content-center total-number">

            <div class="row ">
              <!--Total number of register employers /.col-->
              <?php if(getUserRole(Auth()->user()->id) == 'Super Admin' || getUserRole(Auth()->user()->id) == 'Admin'): ?>
                <div class="col-sm-6 col-lg-3">
                    
                  <div class="card text-white ">
                    <div class="card-body">

                      <div class="text-value"><?php echo e(isset($employerUserCount) ? $employerUserCount : 0); ?></div>
                      <div><b>Total number of registered employers</b></div>
                    </div>


                  </div>
                
                </div>
              <?php endif; ?>

              <!-- Total number of register recruiters  /.col-->

              <?php if(getUserRole(Auth()->user()->id) == 'Super Admin' || getUserRole(Auth()->user()->id) == 'Admin'): ?>
                <div class="col-sm-6 col-lg-3">
                    
                  <div class="card text-white ">
                    <div class="card-body">

                      <div class="text-value"><?php echo e(isset($recruiterUserCount) ? $recruiterUserCount : 0); ?></div>
                      <div><b>Total number of registered recruiters</b></div>
                    </div>

                  </div>
                    
                </div>
              <?php endif; ?>


              <!-- Total number of published jobs /.col-->
              <?php if(getUserRole(Auth()->user()->id) == 'Super Admin' || getUserRole(Auth()->user()->id) == 'Admin' || getUserRole(Auth()->user()->id) == 'Employer'  ): ?>
                <div class="col-sm-6 col-lg-3">
                    
                  <div class="card text-white ">
                    <div class="card-body">

                      <div class="text-value"><?php echo e(isset($jobPostRequestCnt) ? $jobPostRequestCnt : 0); ?></div>
                      <div><b>Total number of jobs</b></div>
                    </div>

                  </div>
                
                </div>
              <?php endif; ?>

              <!-- Total number of Active jobs /.col-->

                <div class="col-sm-6 col-lg-3">
                    
                  <div class="card text-white ">
                    <div class="card-body">

                      <div class="text-value"><?php echo e(isset($activeJobPostCnt) ? $activeJobPostCnt : 0); ?></div>
                      <div><b>Total number of Active jobs</b></div>
                    </div>


                  </div>
                
                </div>


                <?php if(getUserRole(Auth()->user()->id) == 'Recruiter' ): ?>

                <div class="col-sm-6 col-lg-3">
                  
                <div class="card text-white ">
                  <div class="card-body">

                    <div class="text-value"><?php echo e(isset($totelcandidateCnt) ? $totelcandidateCnt : 0); ?></div>
                    <div><b>Total number of candidates in DB</b></div>
                  </div>

                </div>
              
              </div>


              <div class="col-sm-6 col-lg-3">
                
              <div class="card text-white ">
                <div class="card-body">

                  <div class="text-value"><?php echo e(isset($candidatehiredCnt) ? $candidatehiredCnt : 0); ?></div>
                  <div><b>Total number of Hired candidates</b></div>
                </div>

              </div>
            
            </div>


            <div class="col-sm-6 col-lg-3">
              
            <div class="card text-white ">
              <div class="card-body">

                <div class="text-value"><?php echo e(isset($inProcessCandidateCnt) ? $inProcessCandidateCnt : 0); ?></div>
                <div><b>Total number of candidates in pipeline</b></div>
              </div>

            </div>
          
          </div>


                <?php endif; ?>
              <?php if(getUserRole(Auth()->user()->id) == 'Employer' ): ?>

              <div class="col-sm-6 col-lg-3">
                
                  <div class="card text-white ">
                    <div class="card-body">

                      <div class="text-value"><?php echo e(isset($inProcessCandidateCnt) ? $inProcessCandidateCnt : 0); ?></div>
                      <div><b>Total number of candidate In Process</b></div>
                    </div>

                  </div>
                
            </div>

            <div class="col-sm-6 col-lg-3">
              
                <div class="card text-white ">
                  <div class="card-body">

                    <div class="text-value"><?php echo e(isset($closedJobCnt) ? $closedJobCnt : 0); ?></div>
                    <div><b>Total number of closed<br> jobs</b></div>
                  </div>

                </div>
              
          </div>

          <?php endif; ?>

            </div>

            <div class="row">
                <!-- Total number of candidate selected /.col-->
                <?php if(getUserRole(Auth()->user()->id) == 'Super Admin' || getUserRole(Auth()->user()->id) == 'Admin' || getUserRole(Auth()->user()->id) == 'Employer' ): ?>
                    <div class="col-sm-6 col-lg-3">
                      
                        <div class="card text-white ">
                          <div class="card-body">

                            <div class="text-value"><?php echo e(isset($candidateCnt) ? $candidateCnt : 0); ?></div>
                            <div><b>Total number of candidate selected</b></div>
                          </div>

                        </div>
                      
                    </div>
                <?php endif; ?>

                 <!-- Total number of candidate selected /.col-->
                 <?php if(getUserRole(Auth()->user()->id) == 'Super Admin' || getUserRole(Auth()->user()->id) == 'Admin' || getUserRole(Auth()->user()->id) == 'Employer' ): ?>
                  <div class="col-sm-6 col-lg-3">
                    
                      <div class="card text-white ">
                        <div class="card-body">

                          <div class="text-value"><?php echo e(isset($vacanciesCnt) ? $vacanciesCnt : 0); ?></div>
                          <div><b>Total number of vacancies published</b></div>
                        </div>

                      </div>
                    
                  </div>
                <?php endif; ?>




                <!--Total number of InActive jobs /.col-->
                <?php if(getUserRole(Auth()->user()->id) == 'Employer' ): ?>
                  <div class="col-sm-6 col-lg-3">
                      
                        <div class="card text-white ">
                          <div class="card-body">

                            <div class="text-value"><?php echo e(isset($saveCandidateCnt) ? $saveCandidateCnt : 0); ?></div>
                            <div><b>Total number of saved candidates</b></div>
                          </div>

                        </div>
                      
                  </div>

                <?php endif; ?>



                <!--Total number of InActive jobs /.col-->
                <?php if(getUserRole(Auth()->user()->id) == 'Super Admin' || getUserRole(Auth()->user()->id) == 'Admin' || getUserRole(Auth()->user()->id) == 'Employer' ): ?>
                  <div class="col-sm-6 col-lg-3">
                      
                        <div class="card text-white ">
                          <div class="card-body">

                            <div class="text-value"><?php echo e(isset($deactiveJobPostCnt) ? $deactiveJobPostCnt : 0); ?></div>
                            <div><b>Total number of InActive jobs</b></div>
                          </div>

                        </div>
                      
                  </div>
                <?php endif; ?>

                    <!--Total number of Candidate in BOD DB /.col-->
                    <?php if(getUserRole(Auth()->user()->id) == 'Super Admin'  ||  getUserRole(Auth()->user()->id) == 'Admin'): ?>
                    <div class="col-sm-6 col-lg-3">
                        
                          <div class="card text-white ">
                            <div class="card-body">

                              <div class="text-value"><?php echo e(isset($BodCandidateCount) ? $BodCandidateCount : 0); ?></div>
                              <div><b>Total number of Candidate in BOD DB</b></div>
                            </div>

                          </div>
                        
                    </div>
                  <?php endif; ?>



                <!-- /.col-->
              </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('scripts'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('scripts'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/bod/resources/views/home.blade.php ENDPATH**/ ?>