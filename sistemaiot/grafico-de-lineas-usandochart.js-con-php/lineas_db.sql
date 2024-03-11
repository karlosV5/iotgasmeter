
CREATE TABLE `tbl_ventas` (
  `ventas_id` int(11) NOT NULL,
  `monto` double NOT NULL,
  `venta_fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbl_ventas`
--

INSERT INTO `tbl_ventas` (`ventas_id`, `monto`, `venta_fecha`) VALUES
(8, 100, '2020-01-01'),
(9, 55, '2019-01-01'),
(10, 200, '2020-02-02'),
(11, 55, '2019-02-02'),
(12, 175, '2020-03-03'),
(13, 150, '2019-03-03'),
(14, 150, '2020-04-04'),
(15, 85, '2019-04-04'),
(16, 99, '2020-04-04'),
(17, 20, '2019-04-04'),
(18, 180, '2020-05-05'),
(19, 70, '2019-05-05'),
(20, 225, '2019-06-06'),
(21, 150, '2020-06-06'),
(22, 120, '2020-07-07'),
(23, 55, '2019-07-07'),
(24, 199, '2020-08-08'),
(25, 45, '2019-08-08'),
(26, 130, '2020-09-09'),
(27, 75, '2019-09-09'),
(28, 300, '2019-10-10'),
(29, 35, '2019-10-10'),
(30, 250, '2019-11-11'),
(31, 20, '2019-11-11'),
(32, 220, '2020-08-12'),
(33, 200, '2019-12-12'),
(34, 45, '2019-01-05'),
(35, 50, '2020-10-02'),
(36, 300, '2020-10-05'),
(37, 100, '2020-10-06');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_ventas`
--
ALTER TABLE `tbl_ventas`
  ADD PRIMARY KEY (`ventas_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_ventas`
--
ALTER TABLE `tbl_ventas`
  MODIFY `ventas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

