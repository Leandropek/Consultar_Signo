import { exec } from 'child_process';

export default function handler(req, res) {
    exec("php api/index.php", (error, stdout, stderr) => {
        if (error) {
            res.status(500).send(stderr);
            return;
        }
        res.setHeader("Content-Type", "text/html");
        res.send(stdout);
    });
}
