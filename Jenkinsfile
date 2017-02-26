#!groovy

node('tst.wimf.space') { 
   //  stage('Checkout') {
   //      checkout scm
   // }
   //  stage('Build') { 
   //      parallel php: {
   //          sh 'cp /var/www/laravel/.env .'
   //          sh 'composer update'
   //      },
   //      js: {
   //          checkout scm
   //          sh 'npm install'
   //      }
   //  }
    // stage('Test') {
    //     parallel php: {
    //         sh 'vendor/phpunit/phpunit/phpunit'
    //     },
    //     js: {
    //         withEnv(['CI=true']) {
    //             sh 'npm test'
    //         }            
    //     }
    // }
    stage('Deploy') {

        if (env.BRANCH_NAME == 'develop') {
            sh 'echo develop'
            sshagent(['1d637c77-7dd7-4ac6-9c28-7d1c5be3dce6']) {
                sh '/home/athill/bin/deploy_informed'
            }
        } else if (env.BRANCH_NAME == 'master') {
            sh 'echo master'
            sshagent(['3a4ff721-3273-48fd-8493-b6e3b157afb1']) {
                sh '/home/athill/bin/deploy_informed'
            }            
        } else {
            echo "Successfully built and tested ${env.BRANCH_NAME}"
        }
   }
}
