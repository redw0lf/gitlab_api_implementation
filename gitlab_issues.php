<?PHP

// make sure to have a version of pecl_http installed


// Stored token could also be retrieved via $_GET[]
// in the outgoing webhook from slack you have to do sth like:
// http://yourdomain.com/gitlab_issues.php?STORED_TOKEN=[your_gitlab_token]&PROJECT_ID=[your_project_id]
// then you could retrieve the variables like $_GET['STORED_TOKEN'] and
// $_GET['PROJECT_ID']
$SLACK_TOKEN = "aaaaaaaa"; // your slack token
$STORED_TOKEN = "bbbbbb"; // your gitlab token
$PROJECT_ID = 33; // project_id you want to use
$GITLAB_URL = "https://gitlab.example.com:443";// url to your gitlab installation and api stuff

//add api path to url
$GITLAB_URL = $GITLAB_URL . "/api/v3/projects/" $PROJECT_ID . "/issues";


$text = $_POST['text'];
$token = $_POST['token'];
header('Content-Type: application/json');
// check
if ($token === $SLACK_TOKEN) {


    $issue_text_parts = explode(" ", $text);

    $label = $issue_text_parts[1];
    $issue_topic = implode(" ",array_slice($issue_text_parts,2));

    $data = [
        "title" => $issue_topic,
        "labels" => $label
    ];

    $queryString = new http\QueryString($data);

    $url = new http\Url($GITLAB_URL);
    $request = new http\Client\Request("POST",
        $url,
        ["PRIVATE-TOKEN" =>  $STORED_TOKEN]
    );

    $request->setQuery($queryString);



    try {
        $client = new http\Client;
        $client->setOptions(["ssl" => [
            "version" => http\Client\Curl\SSL_VERSION_ANY
        ]]);

        $client->enqueue($request)->send();
        $response = $client->getResponse($request);
        // response code should be 201 -> created new event
        if ($response->getResponseCode() == 201) {
            echo json_encode(array("text" => "Issue created successfully."));
        }
        else {
            echo json_encode(array("text" => "Issue creation failed."));
        }
    } catch (Exception $ex) {
        echo json_encode(array("text" => "Issue creation failed."));
    }

} else {
    echo json_encode(array("text" => "ERROR: Authentication."));
}

?>
