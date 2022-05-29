-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 08, 2022 at 04:53 AM
-- Server version: 10.3.27-MariaDB-0+deb10u1
-- PHP Version: 7.3.19-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `liveChat`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id` int(11) NOT NULL,
  `userid` text NOT NULL,
  `selector` text NOT NULL,
  `token` text NOT NULL,
  `expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `email_tokens`
--

CREATE TABLE `email_tokens` (
  `id` int(11) NOT NULL,
  `user_id` text NOT NULL,
  `selector` text NOT NULL,
  `token` text NOT NULL,
  `code` varchar(255) NOT NULL,
  `expires` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `friend_request`
--

CREATE TABLE `friend_request` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `groupchat`
--

CREATE TABLE `groupchat` (
  `id` int(11) NOT NULL,
  `members` text NOT NULL,
  `owner` text NOT NULL,
  `name` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `banlist` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `groupchat`
--

INSERT INTO `groupchat` (`id`, `members`, `owner`, `name`, `timestamp`, `type`, `description`, `banlist`) VALUES
(107, '155,156', '155', 'Juno', '2022-01-08 04:48:19', 1, 'Offical Juno College Chat', NULL),
(108, '155', '155', 'Cohort 37', '2022-01-08 04:48:57', 1, 'Juno College Cohort 37', NULL),
(109, '155,156', '155', 'Web Devs', '2022-01-08 04:49:22', 1, 'All web dev related stuff', NULL),
(110, '155', '155', 'Private chat', '2022-01-08 04:49:36', 0, 'My private chat', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` text NOT NULL,
  `message` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` int(11) NOT NULL DEFAULT 0,
  `groupid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `status`, `timestamp`, `type`, `groupid`) VALUES
(834, 156, '155,156', '-----BEGIN PGP MESSAGE-----\n\nwf8AAABMA7AgSFMIn64aAQH/YMT1qrB6L1EKnF1xPEr9Fhehfmfm/6QJuSxWPmAAbpFFkudpN0Lh\nMKgXwE+GpQuEb+Fi62Gp3tBMQhOzT/kbqcH/AAAATAPBNqnGv2Re9QECAMkzk89STnLNwAPfmjkR\n065g01ebnxmeQ/jyu7XuzVcqKVk6n2WjcV4QJpZUygkuGYafUaTTtW7yFe//mIoAt/bB/wAAAEwD\nsCBIUwifrhoBAf9QkCQIf5tNes0hRZ+2AdLXxGrWxFDPkJhrIbyZAI2Po2vaLl+wBU/kxsY58pq4\nhx91uV3KPRBswNJy7ZNufamv0v8AAABYAXNTmP7iLBtFd8gmZAcTekJvgH5jO54VaHbf+OI/QVHJ\nxvYsFo4YDqonlSJ6G9ttXCeilV/S/l/8ygSdQWjnuXTjlTMenapFMARmiltX4hFYaEBkN2xLgA==\n=8p9I\n-----END PGP MESSAGE-----\n', 0, '2022-01-08 04:51:29', 1, 107),
(835, 155, '155,156', '-----BEGIN PGP MESSAGE-----\n\nwf8AAABMA7AgSFMIn64aAQH/R+LbfzyXeO8An4qplvpCsYhym3pVHqZ2vS8B1OyqtnFMhDSSM9bD\n7uhQYPe1UrWblO4u/MlDLw6LYIMZbapyc8H/AAAATAPBNqnGv2Re9QECALq0/F5ZfuX1qmok2ETk\nJdWeQiReD9Hs8b1Krn5mNmrkis8YtNBHJ+WXeUv2dXO2qd1FPm2BjJXoUaHiBCukKUbB/wAAAEwD\nwTapxr9kXvUBAgCOasnfzq3kHvEPOz2HxICu+yNLHKxTys+c+1wkgFNM82bWk/Eudhw25l6VEcZO\nU4hdSC0mQ+j4qhmWhQsRE7G00v8AAABOAd2AYnHnK0+QRNKmC40XUyWA5MJ5fwcruMe5uAPc8p2V\n62i0BAsnpBxHyW//DrpebOsHv3AuisgFXL4JVDo3jIBca89uVCnCGaxkxjTT\n=5iJx\n-----END PGP MESSAGE-----\n', 0, '2022-01-08 04:52:05', 1, 107),
(836, 155, '155', '-----BEGIN PGP MESSAGE-----\n\nwf8AAABMA8E2qca/ZF71AQH+JOl9f9kCg2UN+glZdkt3s4IvNBBz/Zk1i+stVxJJdWTZjr+GYRhJ\nVh/YfAFcd0+wcY4/zIRBLD4Oowr2V8pOx8H/AAAATAPBNqnGv2Re9QEB/2u7VnbiEB+F/OvlAYHb\nIBVCanFZZKtivsSD10WwusixWtGsyW1F/F7Ujd03pasj7UFNHdFcewm+ACJYixjcO17S/wAAAEcB\naDFsVqrVvD7TRD3XH7D/5ZBohZma9AAJwoFg0qMczwmSVBkPJIxoaNrbM1huVhCS7dD4Sr8WsxFm\n8VcWU/5waCbwT7Zm4w==\n=gzc5\n-----END PGP MESSAGE-----\n', 0, '2022-01-08 04:52:10', 1, 108),
(837, 155, '156', '-----BEGIN PGP MESSAGE-----\n\nwf8AAABMA7AgSFMIn64aAQH/eDSTjnfNYhQ+aqNsw1p+bZtyUbJd449nFMm9Ad/HuTdiKzYPTP9d\naBhzyoP5PHfb86qgQdXTkBSxgOqivYfqJsH/AAAATAPBNqnGv2Re9QECANcwd0/eQkA7/N8ZRKcW\niKfrz2P5webhROKS3OhvqGeL9Ccp37gghBaqtYhuuRm3Tqyqq2c1i0PzRdQTY9HXon3S/wAAAE4B\nnuEbFVSMlW0hnfnlGX03BtPlAmlVJcVw+01uFKh1vCho2CqBnEsRpMWzSmcciuCeJKBU3ktR8/ny\noU8Nn6rx/Ab7jC+n0ZcZvxe0d8Y=\n=UklA\n-----END PGP MESSAGE-----\n', 1, '2022-01-08 04:52:28', 0, NULL),
(838, 156, '155', '-----BEGIN PGP MESSAGE-----\n\nwf8AAABMA8E2qca/ZF71AQH/dN7s7hdpE9a2HmO7iS1f8Bg5nSgN2767ZcEKKH3ueHVCc8Rr4m6Z\nEeQXGhiX/tunzMnBZfVyeXSWKXQTs8kfo8H/AAAATAOwIEhTCJ+uGgEB/1J5Xt6QMMhOyh9rklTC\nooX3WMJINXxl2j0T5nhrENCTNwYvHOKI5UoLYgHiFBAvn1Ixk/v4nmkV1reDjztoFOvS/wAAAFgB\nJ6zJDmOXhJI46l7gmA9c+p1UX8UJJMvEVnUo8FI0fpeTVJvuTKaaz1hwHJDWPNS3WjqftrdKPmny\nM2mG0Z2yHwaK5xMnsbMmBMJpL+LlCgnMuB2zwSFZ\n=gJb/\n-----END PGP MESSAGE-----\n', 1, '2022-01-08 04:52:53', 0, NULL),
(839, 155, '156', '-----BEGIN PGP MESSAGE-----\n\nwf8AAABMA7AgSFMIn64aAQIAlp6XU+VQoJw6bE/bMne+41FaiTnQd7NcX8ScH0leNgVdjOzNN/wl\nHavvbWERafZl5EDEmdcM+pr+9jtkixEaQsH/AAAATAPBNqnGv2Re9QEB/3leo6r894ODThEsiLKq\nJEfvGA/wscWe7BONg+c12aSMLhMRBIgpF/PzoFS90Q35pCYa9gTZ0AIatjLZvYG63orS/wAAAFAB\nLIFdcgXWBCC26LEhSt3EGje83sCQhspPETBVv5so9beMXpo4lrUFfL+sQmcoGACoK1cMPwmMZUTm\nzmUvefbafmOBKDxm4Z/TFmBGl0cQgw==\n=IwfF\n-----END PGP MESSAGE-----\n', 1, '2022-01-08 04:53:03', 0, NULL),
(840, 155, '156', '-----BEGIN PGP MESSAGE-----\n\nwf8AAABMA7AgSFMIn64aAQH/aJaZTvnsvs5+HxevtCDcBvYDZ14aolbg5Q9g6VtWwE3F7DWoQTVH\nP14rvJ0/V+lGjgQyYyh+yJwCFK80jAjCP8H/AAAATAPBNqnGv2Re9QECANvz7PSN+AiXXq6281K/\n/Dr36kF/pypDC+hqcYgh0Sk+cEeIvKyblmtTqwU9CguR+ftmbHaNULN18wM7gSZ4fOzS/wAAAFAB\nsFLV3ooINPMhN9nuB2yCc59XtM5DSacRu2Yb2LzbfrS4WoWoPWDZi804gDqPRpJvTdY/O3PYp/a7\nFqFAXXULN2P9vTuEEH60EMz/yRc35w==\n=qA/d\n-----END PGP MESSAGE-----\n', 1, '2022-01-08 04:53:06', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `privatekeys`
--

CREATE TABLE `privatekeys` (
  `id` int(11) NOT NULL,
  `uid` text NOT NULL,
  `privatekey` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `privatekeys`
--

INSERT INTO `privatekeys` (`id`, `uid`, `privatekey`) VALUES
(18, 'dallan', '-----BEGIN PGP PRIVATE KEY BLOCK-----\n\nxf8AAAEmBGHZF2oBAgDc1vDPWdzNkwaeIYyHH1/rmXgbLUxCXZUOJmVAAy/fmlGtawep+hJe4TXa\nBaiAm0CrJG1tMy1FhxfM/RlWAS1dABEBAAH+CQMKVl37Kd5gu0tgk8/R8hGy1mDKCnUNIU/OvuIU\nix7VQ9/lTg/0VwD+wXi8P++HSKMwrL4YpX6oiwAEs9YZ0bnorxR4yrQnQclltrC2qQOvYllD53Ss\npIBOBHdJl6TZHTGqUQ7d7vIGiNDHhKZ5La9deyXPV3Bg7mZfPHj5ySwKPwYg5cWtGvKpMowZsaFZ\nIRekdP2eCz1a+QkyU8l+poU1563LEkGoJ6AcqJ0XXiGU83+8w2HZn/rkNq8KFLvAZBeGdJqCse9s\nv4hEcsim71oHhbSUkpqkzf8AAAAAwv8AAABrBBMBCAAf/wAAAAUCYdkXav8AAAACGwP/AAAACRDB\nNqnGv2Re9QAAosgCAKLI4sc7RS/2SL2i0pqmiMvIYUl7y1DZH+QNwSQQ340F7GHrq1eprDEyzmJW\nttlN3gpOQ3smWIO8KEyzKKTu8Ec=\n=VW3C\n-----END PGP PRIVATE KEY BLOCK-----\n'),
(19, 'tester', '-----BEGIN PGP PRIVATE KEY BLOCK-----\n\nxf8AAAEmBGHZGCoBAgC01VFGqIthyoX0RPoFW9igFKz8EZNWtBcE+WY/nX0kom5XTHJeHpMu65QW\nd3IdUYTf+TJeLky0TpUvbROOxCfxABEBAAH+CQMKKNCdd3uXd3FgqtKXfH8mWgTa11/coPPFuFO/\nmhM40dVyj5Jd0kzX8Ilryhw8tEB8gAN8GoMAAixhaQDxDnA89sC5nkjnZX0SW3YFKN0yFBHhnx2h\nF2Jaljfcm/rxIYe0iv3AgQBgT5b5YhxlHrTjwJthmtNtDW3xL5NqZNUnD5pqRNBy0p4UP5Pvhq0+\ns+uTGewKJiPVDU0toqbRYBqpT1kn/ixk4D92vQZs04coSt7VDLFrlpUC7Qp8+1x9491G/wNN3px3\nCjbjGLcnwN45ZabHcWEwzf8AAAAAwv8AAABrBBMBCAAf/wAAAAUCYdkYKv8AAAACGwP/AAAACRCw\nIEhTCJ+uGgAAq5ECAKuRPp58GoAzlfMmA7/u4FmH7NHD/zxI9Ro9HCiQeWCTAY3h/niRzxFHw4+8\nei1MpDbxbwzzw1gyVSyBi3Fqrg8=\n=DD7w\n-----END PGP PRIVATE KEY BLOCK-----\n');

-- --------------------------------------------------------

--
-- Table structure for table `privatepwd`
--

CREATE TABLE `privatepwd` (
  `id` int(11) NOT NULL,
  `uid` text NOT NULL,
  `pwd` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `privatepwd`
--

INSERT INTO `privatepwd` (`id`, `uid`, `pwd`) VALUES
(10, 'dallan', '7bd4234eb6272a03f058cbaa93ee9c'),
(11, 'tester', 'db9ae0c94c17190d4140d9d131279a');

-- --------------------------------------------------------

--
-- Table structure for table `pwdReset`
--

CREATE TABLE `pwdReset` (
  `pwdResetId` int(11) NOT NULL,
  `pwdResetUser` text NOT NULL,
  `pwdResetSelector` text NOT NULL,
  `pwdResetToken` longtext NOT NULL,
  `pwdResetCode` text DEFAULT NULL,
  `pwdResetExpires` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `id` int(6) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL DEFAULT 0,
  `username` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `email` varchar(50000) DEFAULT NULL,
  `verify` int(11) NOT NULL DEFAULT 0,
  `notify` int(1) NOT NULL DEFAULT 0,
  `first_login` int(11) NOT NULL DEFAULT 1,
  `2fa` int(11) NOT NULL DEFAULT 0,
  `friends` text DEFAULT NULL,
  `last_active` text DEFAULT NULL,
  `online_status` text DEFAULT NULL,
  `publickey` longtext DEFAULT NULL,
  `profile_pic` int(11) NOT NULL DEFAULT 0,
  `groupchats` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`date`, `id`, `type`, `username`, `password`, `email`, `verify`, `notify`, `first_login`, `2fa`, `friends`, `last_active`, `online_status`, `publickey`, `profile_pic`, `groupchats`) VALUES
('2022-01-08 04:47:38', 155, 0, 'dallan', '$2y$10$u56h6e34NOx3h3XPwTo0MulgotfRYMPdZM2TXn04AhIpvQrwjAIJK', 'contact@dallan.ca', 0, 0, 0, 0, ',156', '1641617596', '0', '-----BEGIN PGP PUBLIC KEY BLOCK-----\n\nxv8AAABNBGHZF2oBAgDc1vDPWdzNkwaeIYyHH1/rmXgbLUxCXZUOJmVAAy/fmlGtawep+hJe4TXa\nBaiAm0CrJG1tMy1FhxfM/RlWAS1dABEBAAHN/wAAAADC/wAAAGsEEwEIAB//AAAABQJh2Rdq/wAA\nAAIbA/8AAAAJEME2qca/ZF71AACiyAIAosjixztFL/ZIvaLSmqaIy8hhSXvLUNkf5A3BJBDfjQXs\nYeurV6msMTLOYla22U3eCk5DeyZYg7woTLMopO7wRw==\n=5iED\n-----END PGP PUBLIC KEY BLOCK-----\n', 1, '107,108,109,110'),
('2022-01-08 04:50:50', 156, 0, 'tester', '$2y$10$pWBIhRMMDKwMIySGLv785ef0ig3tctMwcixjln8k9oku/Q8Bvmjn2', 'dallanjones@pm.me', 0, 0, 0, 0, ',155', '1641617609', '0', '-----BEGIN PGP PUBLIC KEY BLOCK-----\n\nxv8AAABNBGHZGCoBAgC01VFGqIthyoX0RPoFW9igFKz8EZNWtBcE+WY/nX0kom5XTHJeHpMu65QW\nd3IdUYTf+TJeLky0TpUvbROOxCfxABEBAAHN/wAAAADC/wAAAGsEEwEIAB//AAAABQJh2Rgq/wAA\nAAIbA/8AAAAJELAgSFMIn64aAACrkQIAq5E+nnwagDOV8yYDv+7gWYfs0cP/PEj1Gj0cKJB5YJMB\njeH+eJHPEUfDj7x6LUykNvFvDPPDWDJVLIGLcWquDw==\n=ieGc\n-----END PGP PUBLIC KEY BLOCK-----\n', 0, ',107,109');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_tokens`
--
ALTER TABLE `email_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friend_request`
--
ALTER TABLE `friend_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groupchat`
--
ALTER TABLE `groupchat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `privatekeys`
--
ALTER TABLE `privatekeys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `privatepwd`
--
ALTER TABLE `privatepwd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pwdReset`
--
ALTER TABLE `pwdReset`
  ADD PRIMARY KEY (`pwdResetId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `email_tokens`
--
ALTER TABLE `email_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;
--
-- AUTO_INCREMENT for table `friend_request`
--
ALTER TABLE `friend_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=370;
--
-- AUTO_INCREMENT for table `groupchat`
--
ALTER TABLE `groupchat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=841;
--
-- AUTO_INCREMENT for table `privatekeys`
--
ALTER TABLE `privatekeys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `privatepwd`
--
ALTER TABLE `privatepwd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `pwdReset`
--
ALTER TABLE `pwdReset`
  MODIFY `pwdResetId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
